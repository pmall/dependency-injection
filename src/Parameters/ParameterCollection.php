<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class ParameterCollection implements ParameterCollectionInterface
{
    /**
     * The array of parameters.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface[]
     */
    private $parameters;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface ...$parameters
     */
    public function __construct(ParameterInterface ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Return the array of parameters.
     *
     * @return \Quanta\DI\Parameters\ParameterInterface[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}
