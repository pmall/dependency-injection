<?php declare(strict_types=1);

namespace Quanta\DI;

interface InjectableCallableInterface
{
    /**
     * Return the callable parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array;

    /**
     * Invoke the callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return $mixed
     */
    public function __invoke(...$xs);
}
