<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

final class SomeCallable
{
    /**
     * The callable to invoke.
     *
     * @var callable
     */
    public $callable;

    /**
     * Constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }

    /**
     * Bind the callable to arguments provided by the given argument pool.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     * @return \Quanta\DI\Parameters\ParameterInterface
     */
    public function injected(ArgumentPoolInterface $pool): CallableInterface
    {
        return $this->injectedProxy($this->callable, $pool);
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
     * Return the callable parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    private function parameters(): array
    {
        $reflection = $this->reflection();
        $parameters = $reflection->getParameters();

        return array_map([$this, 'parameter'], $parameters);
    }

    /**
     * Return a reflection of the callable.
     *
     * @return \ReflectionFunctionAbstract
     */
    private function reflection(): \ReflectionFunctionAbstract
    {
        if (is_object($this->callable)) {
            return $this->callable instanceof \Closure
                ? new \ReflectionFunction($this->callable)
                : new \ReflectionMethod($this->callable, '__invoke');
        }

        if (is_array($this->callable)) {
            return new \ReflectionMethod(...$this->callable);
        }

        return new \ReflectionFunction($this->callable);
    }
}
