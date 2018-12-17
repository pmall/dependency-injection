<?php declare(strict_types=1);

namespace Quanta\DI;

final class BoundCallable implements CallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\DI\CallableInterface
     */
    private $callable;

    /**
     * The arguments bound to the first parameter.
     *
     * @var array
     */
    private $arguments;

    /**
     * Constructor.
     *
     * At least one argument is required.
     *
     * @param \Quanta\DI\CallableInterface  $callable
     * @param mixed                         $argument
     * @param mixed                         ...$arguments
     */
    public function __construct(CallableInterface $callable, $argument, ...$arguments)
    {
        $this->callable = $callable;
        $this->arguments = array_merge([$argument], $arguments);
    }

    /**
     * @inheritdoc
     */
    public function parameters(): array
    {
        return $this->callable->parameters();
    }

    /**
     * @inheritdoc
     */
    public function required(): array
    {
        return $this->callable->required();
    }

    /**
     * @inheritdoc
     */
    public function optional(): array
    {
        return $this->callable->optional();
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $required = count($this->required());

        if (count($xs) >= $required) {
            array_splice($xs, $required, 0, $this->arguments);

            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError('Some parameters are not bound to arguments');
    }

    /**
     * Return the default value of the given parameter.
     *
     * @param \Quanta\Parameters\ParameterInterface $parameter
     * @return array
     */
    private function defaults(ParameterInterface $parameter): array
    {
        if ($parameter->hasDefaultValue()) {
            return [$parameter->defaultValue()];
        }

        if ($parameter->allowsNull()) {
            return [null];
        }

        return [];
    }
}
