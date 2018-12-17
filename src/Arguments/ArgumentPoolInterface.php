<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

interface ArgumentPoolInterface
{
    /**
     * Return an array of arguments the given parameter.
     *
     * An array is returned so many values can be returned when the given
     * parameter is variadic.
     *
     * An empty array must be returned when no argument can be inferred for the
     * given parameter.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     * @return array
     */
    public function arguments(ParameterInterface $parameter): array;
}
