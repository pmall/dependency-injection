<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

final class ChainArgumentPool
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * The parameter an argument must be bound to.
     *
     * @var \Quanta\DependencyInjection\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface                         $container
     * @param \Quanta\DependencyInjection\Parameters\ParameterInterface $parameter
     */
    public function __construct(ContainerInterface $container, ParameterInterface $parameter)
    {
        $this->container = $container;
        $this->parameter = $parameter;
    }

    /**
     * Return the argument provided by the given argument pool when the given
     * argument is a placeholder.
     *
     * @param \Quanta\DependencyInjection\Arguments\ArgumentInterface           $argument
     * @param \Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface $pool
     * @return \Quanta\DependencyInjection\Arguments\ArgumentInterface
     */
    public function __invoke(ArgumentInterface $argument, ArgumentPoolInterface $pool): ArgumentInterface
    {
        return ! $argument->hasValue()
            ? $pool->argument($this->container, $this->parameter)
            : $argument;
    }
}
