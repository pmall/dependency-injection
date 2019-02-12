<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

use function Quanta\Exceptions\areAllTypedAs;
use Quanta\Exceptions\ArrayArgumentTypeErrorMessage;

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
        if (! areAllTypedAs('object', $types)) {
            throw new \InvalidArgumentException(
                (string) new ArrayArgumentTypeErrorMessage(2, 'object', $types)
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
