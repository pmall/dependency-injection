<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

abstract class AbstractArgumentPoolDecorator implements ArgumentPoolInterface
{
    /**
     * The decorated argument pool.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface
     */
    private $pool;

    /**
     * Construct.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface $pool
     */
    public function __construct(ArgumentPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        return $this->pool->argument($container, $parameter);
    }
}
