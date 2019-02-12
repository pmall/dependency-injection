<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;

final class CallableParameterSequence implements ParameterSequenceInterface
{
    /**
     * The callable the parameters are used as a parameter sequence.
     *
     * @var callable
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function signature(CallableInterface $callable): SignatureInterface
    {
        return array_reduce($this->parameters(), ...[
            new BindReflectionParameter,
            new CallableAdapter($callable),
        ]);
    }

    /**
     * Return the reflection parameters of the callable.
     *
     * @return \ReflectionParameter[]
     */
    private function parameters(): array
    {
        return $this->reflection()->getParameters();
    }

    /**
     * Return a reflection of the callable.
     *
     * @return \ReflectionFunctionAbstract
     */
    private function reflection(): \ReflectionFunctionAbstract
    {
        if (is_object($this->callable)) {
            return $this->callable instanceof \Closure
                ? new \ReflectionFunction($this->callable)
                : new \ReflectionMethod($this->callable, '__invoke');
        }

        if (is_array($this->callable)) {
            return new \ReflectionMethod(...$this->callable);
        }

        return new \ReflectionFunction(strval($this->callable));
    }
}
