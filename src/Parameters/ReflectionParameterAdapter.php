<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

class ReflectionParameterAdapter implements ParameterInterface
{
    /**
     * The parameter reflection to adapt.
     *
     * @var \ReflectionParameter
     */
    private $reflection;

    /**
     * Constructor.
     *
     * @param \ReflectionParameter $reflection
     */
    public function __construct(\ReflectionParameter $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * @inheritdoc
     */
    public function name(): string
    {
        return '$' . $this->reflection->getName();
    }

    /**
     * @inheritdoc
     */
    public function typeHint(): string
    {
        $type = $this->reflection->getType();

        if (! is_null($type)) {
            return (string) $type;
        }

        throw new \LogicException(
            sprintf('The parameter $%s has no type hint', $this->name())
        );
    }

    /**
     * @inheritdoc
     */
    public function hasTypeHint(): bool
    {
        return $this->reflection->hasType();
    }

    /**
     * @inheritdoc
     */
    public function hasClassTypeHint(): bool
    {
        $type = $this->reflection->getType();

        return ! (is_null($type) || $type->isBuiltIn());
    }

    /**
     * @inheritdoc
     */
    public function defaultValue()
    {
        try {
            return $this->reflection->getDefaultValue();
        }
        catch (\ReflectionException $e) {
            throw new \LogicException(
                sprintf('The parameter $%s has no default value', $this->name())
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function hasDefaultValue(): bool
    {
        return $this->reflection->isDefaultValueAvailable();
    }

    /**
     * @inheritdoc
     */
    public function allowsNull(): bool
    {
        return $this->reflection->allowsNull();
    }

    /**
     * @inheritdoc
     */
    public function isVariadic(): bool
    {
        return $this->reflection->isVariadic();
    }
}
