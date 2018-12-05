<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

interface ArgumentPoolInterface
{
    /**
     * Return an argument for the given parameter.
     *
     * An instance of Placeholder must be returned when no other argument can
     * be inferred for the given parameter.
     *
     * @param \Psr\Container\ContainerInterface                         $container
     * @param \Quanta\DependencyInjection\Parameters\ParameterInterface $parameter
     * @return \Quanta\DependencyInjection\Arguments\ArgumentInterface
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface;
}
