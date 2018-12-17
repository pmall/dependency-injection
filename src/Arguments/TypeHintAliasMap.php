<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Psr\Container\ContainerInterface;

use Quanta\DI\Parameters\ParameterInterface;

final class TypeHintAliasMap implements ArgumentPoolInterface
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * The class name to alias map.
     *
     * @var string[]
     */
    private $aliases;

    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param string[]                          $aliases
     */
    public function __construct(ContainerInterface $container, array $aliases)
    {
        $this->container = $container;
        $this->aliases = $aliases;
    }

    /**
     * @inheritdoc
     */
    public function arguments(ParameterInterface $parameter): array
    {
        if (! $parameter->hasClassTypeHint()) {
            return [];
        }

        $class = $parameter->typeHint();

        if (! key_exists($class, $this->aliases)) {
            return [];
        }

        try {
            $value = $this->container->get($this->aliases[$class]);
        }
        catch (\Throwable $e) {
            throw new \LogicException(
                (string) new ContainerErrorMessage($parameter, $this->aliases[$class]), 0, $e
            );
        }

        if (! $parameter->isVariadic()) {
            return [$value];
        }

        if (is_array($value)) {
            return $value;
        }

        throw new \LogicException(
            (string) new VariadicErrorMessage($parameter, $value)
        );
    }
}
