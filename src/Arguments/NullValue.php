<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class NullValue implements ArgumentPoolInterface
{
    /**
     * @inheritdoc
     */
    public function arguments(ParameterInterface $parameter): array
    {
        if (! $parameter->hasDefaultValue() && ! $parameter->isVariadic()) {
            return $parameter->allowsNull() ? [null] : [];
        }

        return [];
    }
}
