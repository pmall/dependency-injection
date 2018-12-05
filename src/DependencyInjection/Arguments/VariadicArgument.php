<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

use Psr\Container\ContainerInterface;

final class VariadicArgument implements ArgumentInterface
{
    /**
     * The argument values.
     *
     * @var array
     */
    private $values;

    /**
     * Constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
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
        return $this->values;
    }
}
