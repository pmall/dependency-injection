<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Signatures\CallableParameterSequence;
use Quanta\DI\Signatures\ParameterSequenceInterface;
use Quanta\DI\Signatures\ConstructorParameterSequence;

final class Autowirable implements AutowirableInterface
{
    /**
     * The callable to autowire.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The parameter sequence arguments must be bound to.
     *
     * @var \Quanta\DI\Signatures\ParameterSequenceInterface
     */
    private $sequence;

    /**
     * Return a new Autowirable from the given callable.
     *
     * @param callable $callable
     * @return \Quanta\DI\Autowirable
     */
    public static function fromCallable(callable $callable): Autowirable
    {
        return new Autowirable(
            new CallableAdapter($callable),
            new CallableParameterSequence($callable)
        );
    }

    /**
     * Return a new Autowirable from the given class name.
     *
     * @param string $class
     * @return \Quanta\DI\Autowirable
     */
    public static function fromClass(string $class): Autowirable
    {
        return new Autowirable(
            new ConstructorAdapter($class),
            new ConstructorParameterSequence($class)
        );
    }

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface                      $callable
     * @param \Quanta\DI\Signatures\ParameterSequenceInterface  $sequence
     */
    public function __construct(CallableInterface $callable, ParameterSequenceInterface $sequence)
    {
        $this->callable = $callable;
        $this->sequence = $sequence;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ArgumentPoolInterface $pool, ...$xs)
    {
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
