<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class ReflectionParameterAdapter implements ParameterInterface
{
    /**
     * The parameter reflection.
     *
     * @var \ReflectionParameter
     */
    private $reflection;

    /**
     * Constructor.
     *
     * Variadic parameters can't be adapted.
     *
     * @param \ReflectionParameter $reflection
     * @throws \InvalidArgumentException
     */
    public function __construct(\ReflectionParameter $reflection)
    {
        if ($reflection->isVariadic()) {
            throw new \InvalidArgumentException(
                vsprintf('Variadic parameter $%s can\'t be used as a %s', [
                    $reflection->getName(),
                    ParameterInterface::class,
                ])
            );
        }

        $this->reflection = $reflection;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return $this->reflection->getName();
    }

    /**
     * @inheritdoc
     */
    public function hasTypeHint(): bool
    {
        $type = $this->reflection->getType();

        return ! is_null($type) && ! $type->isBuiltIn();
    }

    /**
     * @inheritdoc
     */
    public function typeHint(): TypeHint
    {
        $type = $this->reflection->getType();

        if (! is_null($type) && ! $type->isBuiltIn()) {
            return new TypeHint($type->getName(), $this->reflection->allowsNull());
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
        return $this->reflection->isDefaultValueAvailable()
            || $this->reflection->allowsNull();
    }

    /**
     * @inheritdoc
     */
    public function defaultValue()
    {
        if ($this->reflection->isDefaultValueAvailable()) {
            return $this->reflection->getDefaultValue();
        }

        if ($this->reflection->allowsNull()) {
            return null;
        }

        throw new \LogicException(
            (string) new DefaultValueErrorMessage($this)
        );
    }
}
