<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentInterface;

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
    public function unbound(bool ...$vector): array
    {
        $vector[] = $this->argument->isPlaceholder();

        return $this->callable->unbound(...$vector);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        // number of expected arguments === number of unbound parameters.
        $expected = count($this->unbound());

        // fail when there is less given arguments than expected.
        // inject the argument values into the given arguments.
        if (count($xs) >= $expected) {
            array_splice($xs, $expected, 0, $this->argument->values());

            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError('Some parameters are not bound to arguments');
    }
}
