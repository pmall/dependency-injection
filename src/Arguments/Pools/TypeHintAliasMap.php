<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

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
        if (! $parameter->hasClassTypeHint()) {
            return new Placeholder;
        }

        $class = $parameter->typeHint();

        if (! key_exists($class, $this->aliases)) {
            return new Placeholder;
        }

        try {
            $value = $container->get($this->aliases[$class]);
        }
        catch (\Throwable $e) {
            throw new \LogicException(
                (string) new ContainerErrorMessage($parameter, $this->aliases[$class]), 0, $e
            );
        }

        if (! $parameter->isVariadic()) {
            return new Argument($value);
        }

        if (is_array($value)) {
            return new VariadicArgument($value);
        }

        throw new \LogicException(
            (string) new VariadicErrorMessage($parameter, $value)
        );
    }
}
