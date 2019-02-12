<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\CompositeArgumentPool;
use Quanta\DI\Signatures\Signature;
use Quanta\DI\Signatures\SignatureInterface;
use Quanta\DI\Signatures\CallableParameterSequence;
use Quanta\DI\Signatures\ConstructorParameterSequence;

final class Autowired
{
    /**
     * The callable signature.
     *
     * @var \Quanta\DI\Signatures\SignatureInterface
     */
    private $signature;

    /**
     * The pools providing arguments for the autowirable value.
     *
     * @var \Quanta\DI\Arguments\ArgumentPoolInterface[]
     */
    private $pools;

    /**
     * Return a new Autowired from the given callable.
     *
     * @param callable                                      $callable
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface    ...$pools
     * @return \Quanta\DI\Autowired
     */
    public static function fromCallable(callable $callable, ArgumentPoolInterface ...$pools): Autowired
    {
        return new Autowired(
            new Signature(
                new CallableAdapter($callable),
                new CallableParameterSequence($callable)
            ),
            ...$pools
        );
    }

    /**
     * Return a new Autowired from the given class name.
     *
     * @param string                                        $class
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface    ...$pools
     * @return \Quanta\DI\Autowired
     */
    public static function fromClass(string $class, ArgumentPoolInterface ...$pools): Autowired
    {
        return new Autowired(
            new Signature(
                new ConstructorAdapter($class),
                new ConstructorParameterSequence($class)
            ),
            ...$pools
        );
    }

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Signatures\SignatureInterface      $signature
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface    ...$pools
     */
    public function __construct(SignatureInterface $signature, ArgumentPoolInterface ...$pools)
    {
        $this->signature = $signature;
        $this->pools = $pools;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $bound = $this->signature->bound(
            new CompositeArgumentPool(...$this->pools)
        );

        if (count($xs) >= $bound->placeholders()->number()) {
            return $bound(...$xs);
        }

        throw new \LogicException(
            vsprintf('Unable to autowire function %s(): no argument bound to parameters [%s]', [
                $bound->str(),
                $bound->placeholders()->from(count($xs))->str('$%s'),
            ])
        );
    }
}
