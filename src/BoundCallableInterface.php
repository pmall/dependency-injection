<?php declare(strict_types=1);

namespace Quanta\DI;

interface BoundCallableInterface
{
    /**
     * Return the expected number of argument.
     *
     * @return int
     */
    public function expected(): int;

    /**
     * Return the unbound parameters.
     *
     * @param bool ...$vector
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function unbound(bool ...$vector): array;

    /**
     * Invoke the callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs);
}
