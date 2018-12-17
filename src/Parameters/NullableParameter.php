<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class NullableParameter implements ParameterInterface
{
    /**
     * The parameter.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     */
    public function __construct(ParameterInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return $this->parameter->name();
    }

    /**
     * @inheritdoc
     */
    public function typeHint(): string
    {
        return $this->parameter->typeHint();
    }

    /**
     * @inheritdoc
     */
    public function hasTypeHint(): bool
    {
        return $this->parameter->hasTypeHint();
    }

    /**
     * @inheritdoc
     */
    public function hasClassTypeHint(): bool
    {
        return $this->parameter->hasClassTypeHint();
    }

    /**
     * @inheritdoc
     */
    public function defaultValue()
    {
        return $this->parameter->defaultValue();
    }

    /**
     * @inheritdoc
     */
    public function hasDefaultValue(): bool
    {
        return $this->parameter->hasDefaultValue();
    }

    /**
     * @inheritdoc
     */
    public function allowsNull(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isVariadic(): bool
    {
        return $this->parameter->isVariadic();
    }
}
