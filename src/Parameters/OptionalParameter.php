<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class OptionalParameter implements ParameterInterface
{
    /**
     * The parameter.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * The parameter default value.
     *
     * @var mixed
     */
    private $default;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @param mixed                                     $default
     */
    public function __construct(ParameterInterface $parameter, $default)
    {
        $this->parameter = $parameter;
        $this->default = $default;
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
        return $this->default;
    }

    /**
     * @inheritdoc
     */
    public function hasDefaultValue(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function allowsNull(): bool
    {
        return $this->parameter->allowsNull();
    }

    /**
     * @inheritdoc
     */
    public function isVariadic(): bool
    {
        return $this->parameter->isVariadic();
    }
}
