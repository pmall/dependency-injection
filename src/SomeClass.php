<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;
use Quanta\DI\Parameters\ParameterCollectionInterface;

final class SomeClass implements ParameterCollectionInterface
{
    /**
     * The class name.
     *
     * @var string
     */
    private $class;

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
}
