<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class NameValueMap implements ArgumentPoolInterface
{
    /**
     * The parameter name to value map.
     *
     * @var array
     */
    private $values;

    /**
     * Constructor.
     *
     * @param array values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        $name = $parameter->name();

        if (array_key_exists($name, $this->values)) {
            $value = $this->values[$name];

            if (! $parameter->isVariadic()) {
                return new Argument($value);
            }

            if (is_array($value)) {
                return new VariadicArgument($value);
            }

            throw new \LogicException(
                vsprintf('Parameter %s is variadic and must therefore be associated with an array of values, %s given', [
                    $parameter->name(),
                    gettype($value),
                ])
            );
        }

        return new Placeholder;
    }
}
