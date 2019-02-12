<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\DI\Parameters\ReflectionParameterAdapter;

final class BindReflectionParameter
{
    /**
     * Return a new signature from the given signature and reflection parameter.
     *
     * @param \Quanta\DI\Signatures\SignatureInterface  $signature
     * @param \ReflectionParameter                      $reflection
     * @return \Quanta\DI\Signatures\SignatureInterface
     */
    public function __invoke(SignatureInterface $signature, \ReflectionParameter $reflection): SignatureInterface
    {
        return ! $reflection->isVariadic()
            ? new SignatureWithParameter($signature, new ReflectionParameterAdapter($reflection))
            : $signature;
    }
}
