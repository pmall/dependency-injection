<?php declare(strict_types=1);

namespace Quanta\DI;

final class CallableAdapter implements BoundCallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var callable
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param callable
     */
    public function __construct(callable $callable)
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
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }
}
