<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Quanta\DI\Parameters\ParameterInterface;

final class ContainerErrorMessage
{
    /**
     * The parameter bound to a container entry.
     *
     * @var \Quanta\DI\Parameters\ParameterInterface
     */
    private $parameter;

    /**
     * The id of the entry the container failed to provide.
     *
     * @var string
     */
    private $id;

    /**
     * Constructor.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface  $parameter
     * @param string                                    $id
     */
    public function __construct(ParameterInterface $parameter, string $id)
    {
        $this->parameter = $parameter;
        $this->id = $id;
    }

    public function __toString()
    {
        return vsprintf('Parameter $%s is bound to the failing container entry \'%s\'', [
            $this->parameter->name(),
            $this->id,
        ]);
    }
}
