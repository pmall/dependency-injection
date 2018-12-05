<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

use Psr\Container\ContainerInterface;

interface ArgumentInterface
{
    /**
     * Return whether the argument has value or not.
     *
     * @return bool
     */
    public function hasValue(): bool;

    /**
     * Return an array of the argument values.
     *
     * An array because the argument can be variadic.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return array
     */
    public function values(ContainerInterface $container): array;
}
