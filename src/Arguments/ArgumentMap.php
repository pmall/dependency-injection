<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class ArgumentMap implements ArgumentPoolInterface
{
    /**
     * The parameter name to argument map.
     *
     * @var array
     */
    private $names;

    /**
     * The parameter type hint to instance map.
     *
     * @var object[]
     */
    private $types;

    /**
     * Constructor.
     *
     * @param array     $names
     * @param object[]  $types
     * @throws \InvalidArgumentException
     */
    public function __construct(array $names, array $types = [])
    {
        $result = \Quanta\ArrayTypeCheck::result($types, 'object');

        if (! $result->isValid()) {
            throw new \InvalidArgumentException(
                $result->message()->constructor($this, 2)
            );
        }

        $this->names = $names;
        $this->types = $types;
    }

    /**
     * @inheritdoc
     */
    public function argument(ParameterInterface $parameter): ArgumentInterface
    {
        $name = $parameter->name();

        if (key_exists($name, $this->names)) {
            return new Argument($this->names[$name]);
        }

        if ($parameter->hasTypeHint()) {
            $class = $parameter->typeHint()->class();

            if (key_exists($class, $this->types)) {
                return new Argument($this->types[$class]);
            }
        }

        return new UnboundArgument;
    }
}
