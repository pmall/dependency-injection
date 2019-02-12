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
     * The default value.
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
    public function hasTypeHint(): bool
    {
        if (is_null($this->default)) {
            return $this->parameter->hasTypeHint();
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function typeHint(): TypeHint
    {
        if (is_null($this->default)) {
            return $this->parameter->typeHint();
        }

        throw new \LogicException(
            (string) new TypeHintErrorMessage($this)
        );
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
    public function defaultValue()
    {
        return $this->default;
    }
}
