<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class CompositeArgumentPool implements ArgumentPoolInterface
{
    /**
     * The argument pools.
     *
     * @var \Quanta\DI\Arguments\Pools\ArgumentPoolInterface[]
     */
    private $pools;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Arguments\Pools\ArgumentPoolInterface ...$pools
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

        return array_reduce($this->pools, $reducer, new Placeholder);
    }
}
