<?php declare(strict_types=1);

namespace Quanta\DI;

use Psr\Container\ContainerInterface;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;

final class BindCallable
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * The argument pool.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface
     */
    private $pool;

    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface                 $container
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface  $pool
     */
    public function __construct(ContainerInterface $container, ArgumentPoolInterface $pool)
    {
        $this->container = $container;
        $this->pool = $pool;
    }

    /**
     * Bind the given callable to the argument provided by the argument pool for
     * the given parameter.
     *
     * @param \Quanta\DI\BoundCallableInterface        $callable
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     * @return \Quanta\DI\BoundCallableInterface
     */
    public function __invoke(BoundCallableInterface $callable, ParameterInterface $parameter): BoundCallableInterface
    {
        $argument = $this->pool->argument($this->container, $parameter);

        return new BoundCallable($callable, $argument);
    }
}
