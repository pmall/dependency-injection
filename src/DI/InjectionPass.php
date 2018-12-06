<?php declare(strict_types=1);

namespace Quanta\DI;

use Psr\Container\ContainerInterface;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;

final class InjectionPass
{
    /**
     * The callable.
     *
     * @var callable
     */
    public $callable;

    /**
     * The argument pool providing arguments.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface
     */
    public $pool;

    /**
     * The array of the callable parameters.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface[]
     */
    public $parameters;

    /**
     * Constructor.
     *
     * @param callable                                          $callable
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface  $pool
     * @param \Quanta\DI\Parameters\ParameterInterface          ...$parameters
     */
    public function __construct(callable $callable, ArgumentPoolInterface $pool, ParameterInterface ...$parameters)
    {
        $this->callable = $callable;
        $this->pool = $pool;
        $this->parameters = $parameters;
    }

    /**
     * Bind the callable to arguments using the argument pool.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Quanta\DI\BoundCallableInterface
     */
    public function injected(ContainerInterface $container): BoundCallableInterface
    {
        $bound = new CallableAdapter($this->callable);
        $reducer = new BindCallable($container, $this->pool);

        return array_reduce($this->parameters, $reducer, $bound);
    }
}
