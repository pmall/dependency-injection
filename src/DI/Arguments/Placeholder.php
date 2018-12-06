<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

final class Placeholder implements ArgumentInterface
{
    /**
     * @inheritdoc
     */
    public function values(): array
    {
        return [];
    }
}
