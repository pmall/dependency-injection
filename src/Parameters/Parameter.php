<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class Parameter implements ParameterInterface
{
    /**
     * The parameter name.
     *
     * @var string
     */
    private $name;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return '$' . $this->name;
    }

    /**
     * @inheritdoc
     */
    public function typeHint(): string
    {
        throw new \LogicException(
            sprintf('The parameter $%s has no type hint', $this->name())
        );
    }

    /**
     * @inheritdoc
     */
    public function hasTypeHint(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasClassTypeHint(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function defaultValue()
    {
        throw new \LogicException(
            sprintf('The parameter $%s has no default value', $this->name())
        );
    }

    /**
     * @inheritdoc
     */
    public function hasDefaultValue(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function allowsNull(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isVariadic(): bool
    {
        return false;
    }
}
