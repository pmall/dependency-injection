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
    public function argument(ParameterInterface $parameter): ArgumentInterface
    {
        foreach ($this->pools as $pool) {
            $argument = $pool->argument($parameter);

            if ($argument->isBound()) {
                return $argument;
            }
        }

        return $argument ?? new UnboundArgument;
    }
}
