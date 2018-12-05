<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

final class TypeHintValueMap implements ArgumentPoolInterface
{
    /**
     * The class name to instance map.
     *
     * @var object[]
     */
    private $instances;

    /**
     * Constructor.
     *
     * @param object[] $instances
     */
    public function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        if ($parameter->hasClassTypeHint()) {
            $class = $parameter->typeHint();

            if (array_key_exists($class, $this->instances)) {
                $value = $this->instances[$class];

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
        }

        return new Placeholder;
    }
}
