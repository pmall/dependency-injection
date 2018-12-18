<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;

final class BindingErrorMessage
{
    /**
     * The description of the callable.
     *
     * @var string
     */
    private $description;

    /**
     * The unbound parameters.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface[]
     */
    private $parameters;

    /**
     * Constructor.
     *
     * At least one parameter is required.
     *
     * @param string                                    $description
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @param \Quanta\DI\Parameters\ParameterInterface  ...$parameters
     */
    public function __construct(string $description, ParameterInterface $parameter, ParameterInterface ...$parameters)
    {
        $this->description = $description;
        $this->parameters = array_merge([$parameter], $parameters);
    }

    /**
     * Return the message of the exception thrown when the parameters are not
     * bound to arguments.
     *
     * @return string
     */
    public function __toString()
    {
        $tpl = 'No argument bound to %s %s of %s';

        $names = array_map([$this, 'parameterName'], $this->parameters);

        if (count($names) == 1) {
            return sprintf($tpl, 'parameter', $names[0], $this->description);
        }

        $head = array_slice($names, 0, count($names) - 1);
        $tail = array_slice($names, -1);

        $str = implode(', ', $head) . ' and ' . $tail[0];

        return sprintf($tpl, 'parameters', $str, $this->description);
    }

    /**
     * Return the name of the given parameter.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     * @return string
     */
    private function parameterName(ParameterInterface $parameter): string
    {
        return $parameter->name();
    }
}
