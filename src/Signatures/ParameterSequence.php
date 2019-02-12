<?php declare(strict_types=1);

namespace Quanta\DI\Signatures;

use Quanta\PA\CallableInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class ParameterSequence implements ParameterSequenceInterface
{
    /**
     * The parameters.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface[]
     */
    private $parameters;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface ...$parameters
     */
    public function __construct(ParameterInterface ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function signature(CallableInterface $callable): SignatureInterface
    {
        return array_reduce($this->parameters, ...[
            [$this, 'reduced'],
            new CallableAdapter($callable),
        ]);
    }

    /**
     * Return a new signature from the given signature and parameter.
     *
     * @param \Quanta\DI\Signatures\SignatureInterface $signature
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     * @return \Quanta\DI\Signatures\SignatureInterface
     */
    private function reduced(SignatureInterface $signature, ParameterInterface $parameter): SignatureInterface
    {
        return new SignatureWithParameter($signature, $parameter);
    }
}
