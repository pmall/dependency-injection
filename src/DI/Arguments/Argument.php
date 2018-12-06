<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

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
    public function isPlaceholder(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function values(): array
    {
        return [$this->value];
    }
}
