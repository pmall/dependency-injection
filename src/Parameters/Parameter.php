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
        return $this->name;
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
    public function typeHint(): TypeHint
    {
        throw new \LogicException(
            (string) new TypeHintErrorMessage($this)
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
    public function defaultValue()
    {
        throw new \LogicException(
            (string) new DefaultValueErrorMessage($this)
        );
    }
}
