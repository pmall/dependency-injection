<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

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
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface         $container
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
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
     * @param \Quanta\DI\Arguments\ArgumentInterface            $argument
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface  $pool
     * @return \Quanta\DI\Arguments\ArgumentInterface
     */
    public function __invoke(ArgumentInterface $argument, ArgumentPoolInterface $pool): ArgumentInterface
    {
        return count($argument->values()) == 0
            ? $pool->argument($this->container, $this->parameter)
            : $argument;
    }
}
