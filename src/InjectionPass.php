<?php declare(strict_types=1);

namespace Quanta\DI;

use Psr\Container\ContainerInterface;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterCollectionInterface;

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
     * The parameter collection.
     *
     * @var \Quanta\DI\Parameters\ParameterCollectionInterface
     */
    public $collection;

    /**
     * Constructor.
     *
     * @param callable                                              $callable
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface      $pool
     * @param \Quanta\DI\Parameters\ParameterCollectionInterface    $collection
     */
    public function __construct(callable $callable, ArgumentPoolInterface $pool, ParameterCollectionInterface $collection)
    {
        $this->callable = $callable;
        $this->pool = $pool;
        $this->collection = $collection;
    }

    /**
     * Bind the callable to arguments using the argument pool.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Quanta\DI\BoundCallableInterface
     */
    public function injected(ContainerInterface $container): BoundCallableInterface
    {
        $parameters = $this->collection->parameters();
        $bound = new CallableAdapter($this->callable);
        $reducer = new BindCallable($container, $this->pool);

        return array_reduce($parameters, $reducer, $bound);
    }
}
