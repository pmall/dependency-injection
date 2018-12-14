<?php declare(strict_types=1);

namespace Quanta\DI;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;

final class InjectionPass
{
    /**
     * The blueprint providing the callable and its parameters.
     *
     * @var \Quanta\DI\BlueprintInterface
     */
    public $blueprint;

    /**
     * The argument pool providing arguments.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface
     */
    public $pool;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\BlueprintInterface                     $blueprint
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface  $pool
     */
    public function __construct(BlueprintInterface $blueprint, ArgumentPoolInterface $pool)
    {
        $this->blueprint = $blueprint;
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
        $callable = $this->blueprint->callable();
        $parameters = $this->blueprint->parameters();

        $callable = new CallableAdapter($callable, ...$parameters);
        $reducer = new BindCallable($container, $this->pool);

        return array_reduce($parameters, $reducer, $callable);
    }
}
