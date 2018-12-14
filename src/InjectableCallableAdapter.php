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
    public function unbound(ParameterInterface ...$parameters): array
    {
        return $parameters;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }
}
