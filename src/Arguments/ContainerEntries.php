<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Psr\Container\ContainerInterface;

use Quanta\DI\Parameters\ParameterInterface;

final class ContainerEntries implements ArgumentPoolInterface
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * The parameter name to alias map.
     *
     * @var string[]
     */
    private $names;

    /**
     * The parameter type hint to alias map.
     *
     * @var string[]
     */
    private $types;

    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param string[]                          $names
     * @param string[]                          $types
     * @throws \InvalidArgumentException
     */
    public function __construct(ContainerInterface $container, array $names = [], array $types = [])
    {
        $result = \Quanta\ArrayTypeCheck::result($names, 'string');

        if (! $result->isValid()) {
            throw new \InvalidArgumentException(
                $result->message()->constructor($this, 2)
            );
        }

        $result = \Quanta\ArrayTypeCheck::result($types, 'string');

        if (! $result->isValid()) {
            throw new \InvalidArgumentException(
                $result->message()->constructor($this, 3)
            );
        }

        $this->container = $container;
        $this->names = $names;
        $this->types = $types;
    }

    /**
     * @inheritdoc
     */
    public function argument(ParameterInterface $parameter): ArgumentInterface
    {
        if (! $parameter->hasTypeHint()) {
            return new UnboundArgument;
        }

        $name = $parameter->name();

        if (key_exists($name, $this->names)) {
            return new ContainerEntry($this->container, ...[
                $this->names[$name],
                false,
            ]);
        }

        $type = $parameter->typeHint();
        $class = $type->class();

        if (key_exists($class, $this->types)) {
            return new ContainerEntry($this->container, ...[
                $this->types[$class],
                false,
            ]);
        }

        return new ContainerEntry($this->container, ...[
            $class,
            $type->isNullable(),
        ]);
    }
}
