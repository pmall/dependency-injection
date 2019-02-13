<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\CompositeArgumentPool;
use Quanta\DI\Signatures\CallableParameterSequence;
use Quanta\DI\Signatures\ParameterSequenceInterface;
use Quanta\DI\Signatures\ConstructorParameterSequence;

final class Autowired
{
    /**
     * The callable to autowire.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The sequence of parameters used to retrieve arguments from the pool.
     *
     * @var \Quanta\DI\Signatures\ParameterSequenceInterface
     */
    private $sequence;

    /**
     * The pools providing arguments for the parameters.
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
            new CallableAdapter($callable),
            new CallableParameterSequence($callable),
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
            new ConstructorAdapter($class),
            new ConstructorParameterSequence($class),
            ...$pools
        );
    }

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface                      $callable
     * @param \Quanta\DI\Signatures\ParameterSequenceInterface  $sequence
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface        ...$pools
     */
    public function __construct(
        CallableInterface $callable,
        ParameterSequenceInterface $sequence,
        ArgumentPoolInterface ...$pools
    ) {
        $this->callable = $callable;
        $this->sequence = $sequence;
        $this->pools = $pools;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $pool = new CompositeArgumentPool(...$this->pools);

        $bound = $this->sequence->signature($this->callable)->bound($pool);

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
