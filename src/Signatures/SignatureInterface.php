<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;
use Quanta\DI\Arguments\ArgumentPoolInterface;

interface SignatureInterface
{
    /**
     * Return a bound callable using the given argument pool.
     *
     * @param \Quanta\DI\Arguments\ArgumentPoolInterface $pool
     * @return \Quanta\PA\CallableInterface
     */
    public function bound(ArgumentPoolInterface $pool): CallableInterface;
}
