<?php declare(strict_types=1);

namespace Quanta\DI;

interface BlueprintInterface
{
    /**
     * Return the callable parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array;

    /**
     * Return the callable to invoke.
     *
     * @return callable
     */
    public function callable(): callable;
}
