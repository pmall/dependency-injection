<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\PA\CallableInterface;
use Quanta\PA\CallableWithArgument;

final class Argument implements ArgumentInterface
{
    /**
     * The argument.
     *
     * @var mixed
     */
    private $argument;

    /**
     * Constructor.
     *
     * @param mixed $argument
     */
    public function __construct($argument)
    {
        $this->argument = $argument;
    }

    /**
     * @inheritdoc
     */
    public function isBound(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function bound(CallableInterface $callable): CallableInterface
    {
        return new CallableWithArgument($callable, $this->argument);
    }
}
