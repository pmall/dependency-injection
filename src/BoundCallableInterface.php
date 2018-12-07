<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;

interface BoundCallableInterface
{
    /**
     * Return the expected number of argument.
     *
     * @return int
     */
    public function expected(): int;

    /**
     * Return the unbound parameters from the given array of parameters.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface ...$parameters
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function unbound(ParameterInterface ...$parameters): array;

    /**
     * Invoke the callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs);
}
