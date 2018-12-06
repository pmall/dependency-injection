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
     * Invoke the callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs);
}
