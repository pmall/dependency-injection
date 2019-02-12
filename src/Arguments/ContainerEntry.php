<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Quanta\PA\CallableInterface;
use Quanta\PA\CallableWithArgument;

final class ContainerEntry implements ArgumentInterface
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * The entry id.
     *
     * @var string
     */
    private $id;

    /**
     * Whether the argument can be null.
     *
     * @var bool
     */
    private $nullable;

    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param string                            $id
     * @param bool                              $nullable
     */
    public function __construct(ContainerInterface $container, string $id, bool $nullable)
    {
        $this->container = $container;
        $this->id = $id;
        $this->nullable = $nullable;
    }

    /**
     * @inheritdoc
     */
    public function isBound(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function bound(CallableInterface $callable): CallableInterface
    {
        return new CallableWithArgument($callable, $this->entry());
    }

    /**
     * Return the container entry.
     *
     * Null is returned when the argument can be null and the container entry is
     * not found.
     *
     * @return mixed
     */
    private function entry()
    {
        if ($this->nullable && ! $this->container->has($this->id)) {
            return null;
        }

        try {
            return $this->container->get($this->id);
        }

        catch (NotFoundExceptionInterface $e) {
            if ($this->nullable) {
                return null;
            }

            throw $e;
        }
    }
}
