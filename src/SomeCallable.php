<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;
use Quanta\DI\Parameters\ParameterCollectionInterface;

final class SomeCallable implements ParameterCollectionInterface
{
    /**
     * The callable.
     *
     * @var callable
     */
    private $callable;

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
     * Return the callable parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array
    {
        $reflection = $this->reflection();
        $parameters = $reflection->getParameters();

        return array_map([$this, 'parameter'], $parameters);
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
