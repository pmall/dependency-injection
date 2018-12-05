<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

final class CompositeArgumentPool implements ArgumentPoolInterface
{
    /**
     * The argument pools.
     *
     * @var \Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface[]
     */
    private $pools;

    /**
     * Constructor.
     *
     * @param \Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface ...$pools
     */
    public function __construct(ArgumentPoolInterface ...$pools)
    {
        $this->pools = $pools;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        $reducer = new ChainArgumentPool($container, $parameter);

        $argument = array_reduce($this->pools, $reducer, new Placeholder);

        if (count($argument->values()) == 0) {
            return $parameter->hasDefaultValue()
                ? new Argument($parameter->defaultValue())
                : new Placeholder;
        }

        return $argument;
    }
}
