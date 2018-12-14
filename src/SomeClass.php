<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

final class SomeClass implements InjectableCallableInterface
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return new $this->class(...$xs);
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
