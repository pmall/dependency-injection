<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

final class SomeClass
{
    /**
     * The class to instantiate.
     *
     * @var string
     */
    public $class;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * Bind an instantiation of the class to arguments provided by the given
     * argument pool.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     * @return \Quanta\DI\Parameters\ParameterInterface
     */
    public function injected(ArgumentPoolInterface $pool): CallableInterface
    {
        return $this->injectedProxy(new Instantiation($this->class), $pool);
    }

    /**
     * Return the class name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->class;
    }

    /**
     * Bind the given callable to arguments provided by the given argument pool.
     *
     * @param callable                                      $callable
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface    $pool
     * @return \Quanta\DI\Parameters\ParameterInterface
     */
    public function injectedProxy(callable $callable, ArgumentPoolInterface $pool): CallableInterface
    {
        $parameters = $this->parameters();

        return (new Injectable($callable, ...$parameters))->injected($pool);
    }

    /**
     * Return a parameter from the given reflection parameter.
     *
     * @param \ReflectionParameter $parameter
     * @return \Quanta\DI\Parameters\ParameterInterface
     */
    private function parameter(\ReflectionParameter $parameter): ParameterInterface
    {
        return new ReflectionParameterAdapter($parameter);
    }

    /**
     * Return the class constructor parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array
    {
        $reflection = new \ReflectionClass($this->class);

        $constructor = $reflection->getConstructor();

        return ! is_null($constructor)
            ? array_map([$this, 'parameter'], $constructor->getParameters())
            : [];
    }
}
