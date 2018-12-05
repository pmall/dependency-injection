<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

use Psr\Container\ContainerInterface;

final class Placeholder implements ArgumentInterface
{
    /**
     * @inheritdoc
     */
    public function hasValue(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function values(ContainerInterface $container): array
    {
        return [];
    }
}
