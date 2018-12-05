<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

use Psr\Container\ContainerInterface;

final class Argument implements ArgumentInterface
{
    /**
     * The argument value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
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
        return [$this->value];
    }
}
