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
        // number of expected arguments === number of remaining placeholders.
        $expected = $this->expected();

        // fail when there is less given arguments than expected.
        if (count($xs) < $expected) {
            throw new \ArgumentCountError(
                (string) new ArgumentCountErrorMessage($expected, count($xs))
            );
        }

        // inject the argument values into the given arguments.
        array_splice($xs, $expected, 0, $this->argument->values());

        return ($this->callable)(...$xs);
    }
}
