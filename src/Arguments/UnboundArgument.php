<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\PA\CallableInterface;

final class UnboundArgument implements ArgumentInterface
{
    /**
     * @inheritdoc
     */
    public function isBound(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function bound(CallableInterface $callable): CallableInterface
    {
        throw new \LogicException('No argument bound');
    }
}
