<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

abstract class AbstractArgumentPoolDecorator implements ArgumentPoolInterface
{
    /**
     * The decorated argument pool.
     *
     * @var \Quanta\DI\Arguments\ArgumentPoolInterface
     */
    private $pool;

    /**
     * Construct.
     *
     * @var \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     */
    public function __construct(ArgumentPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @inheritdoc
     */
    public function arguments(ParameterInterface $parameter): array
    {
        return $this->pool->arguments($parameter);
    }
}
