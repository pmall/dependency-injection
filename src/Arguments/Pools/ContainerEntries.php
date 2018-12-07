<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class ContainerEntries implements ArgumentPoolInterface
{
    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        if (! $parameter->hasClassTypeHint() || $parameter->isVariadic()) {
            return new Placeholder;
        }

        $class = $parameter->typeHint();

        if (! $container->has($class)) {
            return new Placeholder;
        }

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
