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
     * Return the parameter type hint.
     *
     * @return string
     * @throws \LogicException
     */
    public function typeHint(): string;

    /**
     * Return whether the parameter has a type hint or not.
     *
     * @return bool
     */
    public function hasTypeHint(): bool;

    /**
     * Return whether the parameter has a class type hint or not.
     *
     * @return bool
     */
    public function hasClassTypeHint(): bool;

    /**
     * Return the parameter default value.
     *
     * @return mixed
     * @throws \LogicException
     */
    public function defaultValue();

    /**
     * Return whether the parameter has a default value or not.
     *
     * @return bool
     */
    public function hasDefaultValue(): bool;

    /**
     * Return whether the parameter accepts null values or not.
     *
     * @return bool
     */
    public function allowsNull(): bool;

    /**
     * Return whether the parameter accepts many arguments or not.
     *
     * @return bool
     */
    public function isVariadic(): bool;
}
