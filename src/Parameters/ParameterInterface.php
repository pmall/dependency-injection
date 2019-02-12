<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

interface ParameterInterface
{
    /**
     * Return the parameter name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Return whether the parameter is type hinted.
     *
     * Only class names are considered as type hints.
     *
     * @return bool
     */
    public function hasTypeHint(): bool;

    /**
     * Return the parameter type hint.
     *
     * Should throw a LogicException when ->hasTypeHint() returns false.
     *
     * @return \Quanta\DI\Parameters\TypeHint
     * @throws \LogicException
     */
    public function typeHint(): TypeHint;

    /**
     * Return whether the parameter has a default value.
     *
     * @return bool
     */
    public function hasDefaultValue(): bool;

    /**
     * Return the parameter default value.
     *
     * Should throw a LogicException when ->hasDefaultValue() returns false.
     *
     * @return mixed
     * @throws \LogicException
     */
    public function defaultValue();
}
