<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\Exceptions\ArgumentCountErrorMessage;

final class BoundCallable implements BoundCallableInterface
{
    /**
     * The callable to execute.
     *
     * @var \Quanta\DI\BoundCallableInterface
     */
    private $callable;

    /**
     * The argument to execute the callable with.
     *
     * @var \Quanta\DI\Arguments\ArgumentInterface
     */
    private $argument;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\BoundCallableInterface        $callable
     * @param \Quanta\DI\Arguments\ArgumentInterface   $argument
     */
    public function __construct(BoundCallableInterface $callable, ArgumentInterface $argument)
    {
        $this->callable = $callable;
        $this->argument = $argument;
    }

    /**
     * @inheritdoc
     */
    public function expected(): int
    {
        return $this->callable->expected() + $this->argument->isPlaceholder();
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $values = $this->argument->values();

        // number of expected arguments === number of remaining placeholders.
        $expected = $this->expected();

        // those arguments position within the given argument list is the number
        // of expected arguments.
        array_splice($xs, $expected - count($values), 0, $values);

        // invoke the callable when there is as many arguments as expected.
        if (count($xs) >= $expected) {
            return ($this->callable)(...$xs);
        }

        // fail when there is less arguments than expected.
        throw new \ArgumentCountError(
            (string) new ArgumentCountErrorMessage($expected, count($xs))
        );
    }
}
