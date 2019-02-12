<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;
use Quanta\PA\CallableWithPlaceholder;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class Signature implements SignatureInterface
{
    /**
     * The signature.
     *
     * @var \Quanta\DI\Signatures\SignatureInterface
     */
    private $signature;

    /**
     * The parameter bound to the signature.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Signatures\SignatureInterface $signature
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     */
    public function __construct(SignatureInterface $signature, ParameterInterface $parameter)
    {
        $this->signature = $signature;
        $this->parameter = $parameter;
    }

    /**
     * @inheritdoc
     */
    public function bound(ArgumentPoolInterface $pool): CallableInterface
    {
        $bound = $this->signature->bound($pool);

        $argument = $pool->argument($this->parameter);

        if ($argument->isBound()) {
            return $argument->bound($bound);
        }

        if ($this->parameter->hasDefaultValue()) {
            return new CallableWithPlaceholder($bound, ...[
                $this->parameter->name(),
                $this->parameter->defaultValue(),
            ]);
        }

        return new CallableWithPlaceholder($bound, $this->parameter->name());
    }
}
