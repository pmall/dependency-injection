<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class VariadicErrorMessage
{
    /**
     * The variadic parameter.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * The value which can't be bound to a variadic parameter.
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @param mixed                                     $value
     */
    public function __construct(ParameterInterface $parameter, $value)
    {
        $this->parameter = $parameter;
        $this->value = $value;
    }

    /**
     * Return the message of the exception thrown when trying to bind a non
     * array value to a variadic parameter.
     *
     * @return string
     */
    public function __toString()
    {
        return vsprintf('Parameter %s is variadic and must therefore be associated with an array of values, %s given', [
            $this->parameter->name(),
            gettype($this->value),
        ]);
    }
}
