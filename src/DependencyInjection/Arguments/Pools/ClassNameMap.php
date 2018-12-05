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

final class ClassNameMap implements ArgumentPoolInterface
{
    /**
     * The class name to instance map.
     *
     * @var object[]
     */
    private $instances;

    /**
     * The class name to container id map.
     *
     * @var string[]
     */
    private $aliases;

    /**
     * Constructor.
     *
     * @param object[]  $instances
     * @param string[]  $aliases
     */
    public function __construct(array $instances, array $aliases)
    {
        $this->instances = $instances;
        $this->aliases = $aliases;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        if ($parameter->hasClassTypeHint()) {
            $class = $parameter->typeHint();
            $is_variadic = $parameter->isVariadic();

            if (isset($this->aliases[$class])) {
                return $is_variadic
                    ? new VariadicContainerEntry($this->aliases[$class])
                    : new ContainerEntry($this->aliases[$class]);
            }

            if (isset($this->instances[$class])) {
                $value = $this->instances[$class];

                if (! $is_variadic) {
                    return new Argument($value);
                }

                if (is_array($value)) {
                    return new VariadicArgument($value);
                }
            }
        }

        return new Placeholder;
    }
}
