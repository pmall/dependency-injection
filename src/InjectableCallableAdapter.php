<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;

final class InjectableCallableAdapter implements BoundCallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\DI\InjectableCallableInterface
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\InjectableCallableInterface $callable
     */
    public function __construct(InjectableCallableInterface $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function expected(): int
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function unbound(bool ...$vector): array
    {
        $parameters = $this->callable->parameters();

        $unbound = array_intersect_key(array_values($parameters), array_filter($vector));

        return array_values($unbound);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }
}
