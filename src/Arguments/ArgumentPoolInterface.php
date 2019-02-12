<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

interface ArgumentPoolInterface
{
    /**
     * Return an argument for the given parameter.
     *
     * @param \Quanta\DI\Parameters\ParameterInterface $parameter
     * @return \Quanta\DI\Arguments\ArgumentInterface
     */
    public function argument(ParameterInterface $parameter): ArgumentInterface;
}
