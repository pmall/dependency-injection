<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;

final class UnboundCallable implements CallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\DI\CallableInterface
     */
    private $callable;

    /**
     * The callable first parameter.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\CallableInterface              $callable
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     */
    public function __construct(CallableInterface $callable, ParameterInterface $parameter)
    {
        $this->callable = $callable;
        $this->parameter = $parameter;
    }

    /**
     * @inheritdoc
     */
    public function parameters(): array
    {
        $parameters = $this->callable->parameters();

        return array_merge($parameters, [$this->parameter]);
    }

    /**
     * @inheritdoc
     */
    public function required(): array
    {
        if ($this->isOptional()) {
            return $this->callable->required();
        }

        return $this->parameters();
    }

    /**
     * @inheritdoc
     */
    public function optional(): array
    {
        if (! $this->isOptional()) {
            return [];
        }

        $optional = $this->callable->optional();

        return array_merge($optional, [$this->parameter]);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $required = $this->required();

        if (count($xs) >= count($required)) {
            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            (string) new BindingErrorMessage(
                'injected callable', ...array_slice($required, count($xs))
            )
        );
    }

    /**
     * Return whether a default value can be inferred from the parameter.
     *
     * @return bool
     */
    private function isOptional(): bool
    {
        return $this->parameter->hasDefaultValue()
            || $this->parameter->isVariadic();
    }
}
