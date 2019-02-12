<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;

interface ParameterSequenceInterface
{
    /**
     * Return a signature from the given callable.
     *
     * @param \Quanta\PA\CallableInterface $callable
     * @return \Quanta\DI\Signatures\SignatureInterface
     */
    public function signature(CallableInterface $callable): SignatureInterface;
}
