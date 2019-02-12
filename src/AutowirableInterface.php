<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Arguments\ArgumentPoolInterface;

interface AutowirableInterface
{
    /**
     * Return a value using the given argument pool.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     * @return mixed
     */
    public function __invoke(ArgumentPoolInterface $pool);
}
