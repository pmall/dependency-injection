<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class TypeHintedParameter implements ParameterInterface
{
    /**
     * The parameter.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * The parameter type hint.
     *
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @param string                                    $class
     */
    public function __construct(ParameterInterface $parameter, string $class)
    {
        $this->parameter = $parameter;
        $this->class = $class;
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
        return $this->class;
    }

    /**
     * @inheritdoc
     */
    public function hasTypeHint(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function hasClassTypeHint(): bool
    {
        return true;
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
