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
     * The type hint.
     *
     * @var string
     */
    private $type;

    /**
     * Whether the parameter allows null argument.
     *
     * @var bool
     */
    private $nullable;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @param string                                    $type
     * @param bool                                      $nullable
     */
    public function __construct(ParameterInterface $parameter, string $type, bool $nullable = false)
    {
        $this->parameter = $parameter;
        $this->type = $type;
        $this->nullable = $nullable;
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
        return true;
    }

    /**
     * @inheritdoc
     */
    public function typeHint(): TypeHint
    {
        return new TypeHint($this->type, $this->nullable);
    }

    /**
     * @inheritdoc
     */
    public function hasDefaultValue(): bool
    {
        return $this->nullable;
    }

    /**
     * @inheritdoc
     */
    public function defaultValue()
    {
        if ($this->nullable) {
            return null;
        }

        throw new \LogicException(
            (string) new DefaultValueErrorMessage($this)
        );
    }
}
