<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Psr\Container\ContainerInterface;

use Quanta\DI\Parameters\ParameterInterface;

final class NameAliasMap implements ArgumentPoolInterface
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * The parameter name to container id map.
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
        $name = $parameter->name();

        if (! key_exists($name, $this->aliases)) {
            return [];
        }

        try {
            $value = $this->container->get($this->aliases[$name]);
        }
        catch (\Throwable $e) {
            throw new \LogicException(
                (string) new ContainerErrorMessage($parameter, $this->aliases[$name]), 0, $e
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
