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
        $required = $this->required();

        if (count($xs) >= count($required)) {
            $parameters = $this->parameters();

            for ($i = count($xs); $i < count($parameters); $i++) {
                $arguments = [];

                if ($parameters[$i]->hasDefaultValue()) {
                    $arguments = [$parameters[$i]->defaultValue()];
                }

                $xs = array_merge($xs, $arguments);
            }

            array_splice($xs, count($parameters), 0, $this->arguments);

            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            (string) new BindingErrorMessage(
                'injected callable', ...array_slice($required, count($xs))
            )
        );
    }
}
