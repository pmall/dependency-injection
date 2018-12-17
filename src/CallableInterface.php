<?php declare(strict_types=1);

namespace Quanta\DI;

interface CallableInterface
{
    /**
     * Return the callable parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array;

    /**
     * Return the callable required parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function required(): array;

    /**
     * Return the callable optional parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function optional(): array;

    /**
     * Invoke the callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs);
}
