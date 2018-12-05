<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ContainerEntry;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Arguments\VariadicContainerEntry;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

final class NameMap implements ArgumentPoolInterface
{
    /**
     * The parameter name to value map.
     *
     * @var array
     */
    private $values;

    /**
     * The parameter name to container id map.
     *
     * @var string[]
     */
    private $aliases;

    /**
     * Constructor.
     *
     * @param array     $values
     * @param string[]  $aliases
     */
    public function __construct(array $values, array $aliases)
    {
        $this->values = $values;
        $this->aliases = $aliases;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        $name = $parameter->name();
        $is_variadic = $parameter->isVariadic();

        if (isset($this->aliases[$name])) {
            return $is_variadic
                ? new VariadicContainerEntry($this->aliases[$name])
                : new ContainerEntry($this->aliases[$name]);
        }

        if (isset($this->values[$name])) {
            $value = $this->values[$name];

            if (! $is_variadic) {
                return new Argument($value);
            }

            if (is_array($value)) {
                return new VariadicArgument($value);
            }
        }

        return new Placeholder;
    }
}
