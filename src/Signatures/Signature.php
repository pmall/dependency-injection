<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;
use Quanta\DI\Arguments\ArgumentPoolInterface;

final class Signature implements SignatureInterface
{
    /**
     * The callable arguments must be bound to.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The sequence of parameters used to retrieve arguments from the pool.
     *
     * @var \Quanta\DI\Signatures\ParameterSequenceInterface
     */
    private $sequence;

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface                      $callable
     * @param \Quanta\DI\Signatures\ParameterSequenceInterface  $sequence
     */
    public function __construct(CallableInterface $callable, ParameterSequenceInterface $sequence)
    {
        $this->callable = $callable;
        $this->sequence = $sequence;
    }

    /**
     * @inheritdoc
     */
    public function bound(ArgumentPoolInterface $pool): CallableInterface
    {
        return $this->sequence->signature($this->callable)->bound($pool);
    }
}
