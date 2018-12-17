<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class ChainArgumentPool
{
    /**
     * The parameter arguments must be bound to.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     */
    public function __construct(ParameterInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * Return the arguments provided by the given argument pool when the given
     * argument array is empty.
     *
     * @param array                                         $arguments
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface    $pool
     * @return array
     */
    public function __invoke(array $arguments, ArgumentPoolInterface $pool): array
    {
        return count($arguments) == 0
            ? $pool->arguments($this->parameter)
            : $arguments;
    }
}
