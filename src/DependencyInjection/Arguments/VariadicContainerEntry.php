<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

use Psr\Container\ContainerInterface;

final class VariadicContainerEntry implements ArgumentInterface
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
        return false;
    }

    /**
     * @inheritdoc
     */
    public function values(ContainerInterface $container): array
    {
        $value = $container->get($this->id);

        if (is_array($value)) {
            return $value;
        }

        throw new \LogicException(
            sprintf('Variadic container entry must be an array')
        );
    }
}
