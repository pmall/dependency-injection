<?php declare(strict_types=1);

namespace Quanta\DependencyInjection\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ContainerEntry;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;

final class ContainerEntries implements ArgumentPoolInterface
{
    /**
     * @inheritdoc
     */
    public function argument(ContainerInterface $container, ParameterInterface $parameter): ArgumentInterface
    {
        if ($parameter->hasClassTypeHint() && ! $parameter->isVariadic()) {
            $class = $parameter->typeHint();

            return $container->has($class)
                ? new ContainerEntry($class)
                : new Placeholder;
        }

        return new Placeholder;
    }
}
