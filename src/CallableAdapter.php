<?php declare(strict_types=1);

namespace Quanta\DI;

final class CallableAdapter implements CallableInterface
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
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function parameters(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function required(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function optional(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }
}
