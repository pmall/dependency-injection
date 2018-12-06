<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

interface ArgumentPoolInterface
{
    /**
     * Return an argument for the given parameter.
     *
     * An instance of Placeholder must be returned when no other argument can
     * be inferred for the given parameter.
     *
     * @param \Psr\Container\ContainerInterface         $container
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @return \Quanta\DI\Arguments\ArgumentInterface
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface;
}
