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
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function arguments(ParameterInterface $parameter): array
    {
        if ($parameter->hasClassTypeHint() && ! $parameter->isVariadic()) {
            $class = $parameter->typeHint();

            if ($this->container->has($class)) {
                try {
                    return [$this->container->get($class)];
                }
                catch (\Throwable $e) {
                    throw new \LogicException(
                        (string) new ContainerErrorMessage($parameter, $class), 0, $e
                    );
                }
            }
        }

        return [];
    }
}
