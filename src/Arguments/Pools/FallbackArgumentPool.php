<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class FallbackArgumentPool implements ArgumentPoolInterface
{
    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        if ($parameter->isVariadic()) {
            return new VariadicArgument([]);
        }

        if ($parameter->hasClassTypeHint()) {
            $class = $parameter->typeHint();

            if ($container->has($class)) {
                try {
                    return new Argument($container->get($class));
                }
                catch (\Throwable $e) {
                    throw new \LogicException(
                        (string) new ContainerErrorMessage($parameter, $class), 0, $e
                    );
                }
            }
        }

        if ($parameter->hasDefaultValue()) {
            return new Argument($parameter->defaultValue());
        }

        if ($parameter->allowsNull()) {
            return new Argument(null);
        }

        return new Placeholder;
    }
}
