<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;
use Quanta\DI\Arguments\ArgumentPoolInterface;

final class CallableAdapter implements SignatureInterface
{
    /**
     * The callable to adapt as a signature without parameter.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface $callable
     */
    public function __construct(CallableInterface $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function bound(ArgumentPoolInterface $pool): CallableInterface
    {
        return $this->callable;
    }
}
