<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class Injectable
{
    /**
     * The callable to invoke.
     *
     * @var callable
     */
    public $callable;

    /**
     * The callable parameters.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface[]
     */
    public $parameters;

    /**
     * Constructor.
     *
     * @param callable                                  $callable
     * @param \Quanta\DI\Parameters\ParameterInterface  ...$parameters
     */
    public function __construct(callable $callable, ParameterInterface ...$parameters)
    {
        $this->callable = $callable;
        $this->parameters = $parameters;
    }

    /**
     * Bind the callable to arguments provided by the given argument pool.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     * @return \Quanta\DI\Parameters\ParameterInterface
     */
    public function injected(ArgumentPoolInterface $pool): CallableInterface
    {
        $callable = new CallableAdapter($this->callable);
        $reducer = new BindCallable($pool);

        return array_reduce($this->parameters, $reducer, $callable);
    }
}
