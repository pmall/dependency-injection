<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

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
