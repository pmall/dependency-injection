<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

final class TypeHintAliasMap implements ArgumentPoolInterface
{
    /**
     * The class name to alias map.
     *
     * @var string[]
     */
    private $aliases;

    /**
     * Constructor.
     *
     * @param string[] $aliases
     */
    public function __construct(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        if ($parameter->hasClassTypeHint()) {
            $class = $parameter->typeHint();

            if (array_key_exists($class, $this->aliases)) {
                $value = $container->get($this->aliases[$class]);

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
