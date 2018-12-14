<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;

final class CallableAdapter implements BoundCallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var callable
     */
    private $callable;

    /**
     * The callable parameters.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface[]
     */
    private $parameters;

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
     * @inheritdoc
     */
    public function unbound(bool ...$vector): array
    {
        $unbound = array_intersect_key($this->parameters, array_filter($vector));

        return array_values($unbound);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }
}
