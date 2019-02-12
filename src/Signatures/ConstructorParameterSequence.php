<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;

final class ConstructorParameterSequence implements ParameterSequenceInterface
{
    /**
     * The class the constructor parameters are used as a parameter sequence.
     *
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
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
     * Return the reflection parameters of the class constructor.
     *
     * An empty array is returned when the class does not exist or does not have
     * a constructor.
     *
     * @return \ReflectionParameter[]
     */
    private function parameters(): array
    {
        try {
            $reflection = new \ReflectionClass($this->class);
        }

        catch (\ReflectionException $e) {
            return [];
        }

        $constructor = $reflection->getConstructor();

        return ! is_null($constructor)
            ? $constructor->getParameters()
            : [];
    }
}
