<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class CompositeArgumentPool implements ArgumentPoolInterface
{
    /**
     * The argument pools.
     *
     * @var \Quanta\DI\Arguments\ArgumentPoolInterface[]
     */
    private $pools;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface ...$pools
     */
    public function __construct(ArgumentPoolInterface ...$pools)
    {
        $this->pools = $pools;
    }

    /**
     * @inheritdoc
     */
    public function arguments(ParameterInterface $parameter): array
    {
        return array_reduce($this->pools, new ChainArgumentPool($parameter), []);
    }
}
