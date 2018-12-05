<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments;

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
    public function values(): array
    {
        return $this->values;
    }
}
