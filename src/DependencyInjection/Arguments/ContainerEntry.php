<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

use Psr\Container\ContainerInterface;

final class ContainerEntry implements ArgumentInterface
{
    /**
     * The container entry id.
     *
     * @var string
     */
    private $id;

    /**
     * Constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

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
        return [$container->get($this->id)];
    }
}
