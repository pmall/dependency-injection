<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class TypeHintErrorMessage
{
    /**
     * The parameter.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     */
    public function __construct(ParameterInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * Return the error message of the exception thrown when the parameter has
     * no type hint.
     *
     * @return string
     */
    public function __toString()
    {
        return vsprintf('The parameter $%s has no type hint', [
            $this->parameter->name(),
        ]);
    }
}
