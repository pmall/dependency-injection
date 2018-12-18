<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class BindCallable
{
    /**
     * The argument pool.
     *
     * @var \Quanta\DI\Arguments\ArgumentPoolInterface
     */
    private $pool;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     */
    public function __construct(ArgumentPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    /**
     * Bind the given callable to the argument provided by the argument pool for
     * the given parameter.
     *
     * @param \Quanta\DI\CallableInterface              $callable
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @return \Quanta\DI\CallableInterface
     */
    public function __invoke(CallableInterface $callable, ParameterInterface $parameter): CallableInterface
    {
        $arguments = $this->pool->arguments($parameter);

        return count($arguments) > 0
            ? new BoundCallable($callable, ...$arguments)
            : new UnboundCallable($callable, $parameter);
    }
}
