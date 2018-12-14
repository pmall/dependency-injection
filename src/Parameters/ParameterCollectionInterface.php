<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

interface ParameterCollectionInterface
{
    /**
     * Return an array of parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array;
}
