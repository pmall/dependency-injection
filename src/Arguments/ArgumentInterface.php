<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\PA\CallableInterface;

interface ArgumentInterface
{
    /**
     * Return whether the argument is bound.
     *
     * @return bool
     */
    public function isBound(): bool;

    /**
     * Bind the argument to the given callable.
     *
     * @param \Quanta\PA\CallableInterface $callable
     * @return \Quanta\PA\CallableInterface
     */
    public function bound(CallableInterface $callable): CallableInterface;
}
