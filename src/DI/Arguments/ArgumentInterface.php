<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

interface ArgumentInterface
{
    /**
     * Return an array of the argument values.
     *
     * An array because the argument can be variadic.
     *
     * @return array
     */
    public function values(): array;
}
