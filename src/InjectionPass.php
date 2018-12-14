<?php declare(strict_types=1);

namespace Quanta\DI;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;

final class InjectionPass
{
    /**
     * The injectable callable.
     *
     * @var \Quanta\DI\InjectableCallableInterface
     */
    public $callable;

    /**
     * The argument pool providing arguments.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface
     */
    public $pool;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\InjectableCallableInterface            $callable
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface  $pool
     */
    public function __construct(InjectableCallableInterface $callable, ArgumentPoolInterface $pool)
    {
        $this->callable = $callable;
        $this->pool = $pool;
    }

    /**
     * Bind the callable to arguments using the argument pool.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Quanta\DI\BoundCallableInterface
     */
    public function injected(ContainerInterface $container): BoundCallableInterface
    {
        $parameters = $this->callable->parameters();
        $bound = new InjectableCallableAdapter($this->callable);
        $reducer = new BindCallable($container, $this->pool);

        return array_reduce($parameters, $reducer, $bound);
    }
}
