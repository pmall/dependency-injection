<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

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

            if (key_exists($class, $this->instances)) {
                $value = $this->instances[$class];

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

        return new Placeholder;
    }
}
