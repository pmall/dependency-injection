<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class TypeHintInstanceMap implements ArgumentPoolInterface
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
    public function arguments(ParameterInterface $parameter): array
    {
        if (! $parameter->hasClassTypeHint()) {
            return [];
        }

        $class = $parameter->typeHint();

        if (! key_exists($class, $this->instances)) {
            return [];
        }

        $value = $this->instances[$class];

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
