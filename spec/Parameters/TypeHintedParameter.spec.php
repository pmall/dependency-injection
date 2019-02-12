<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\TypeHint;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\TypeHintedParameter;
use Quanta\DI\Parameters\DefaultValueErrorMessage;

describe('TypeHintedParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(ParameterInterface::class);

    });

    context('when the parameter is not nullable', function () {

        beforeEach(function () {

            $this->parameter = new TypeHintedParameter($this->delegate->get(), ...[
                SomeClass::class,
                false,
            ]);

        });

        it('should implement ParameterInterface', function () {

            expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

        });

        describe('->name()', function () {

            it('should return the delegate name', function () {

                $this->delegate->name->returns('name');

                $test = $this->parameter->name();

                expect($test)->toEqual('name');

            });

        });

        describe('->hasTypeHint()', function () {

            it('should return true', function () {

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeTruthy();

            });

        });

        describe('->typeHint()', function () {

            it('should return the type hint', function () {

                $test = $this->parameter->typeHint();

                expect($test)->toEqual(new TypeHint(SomeClass::class, false));

            });

        });

        describe('->hasDefaultValue()', function () {

            it('should return false', function () {

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeFalsy();

            });

        });

        describe('->defaultValue()', function () {

            it('should throw a LogicException', function () {

                expect([$this->parameter, 'defaultValue'])->toThrow(new LogicException(
                    (string) new DefaultValueErrorMessage($this->parameter)
                ));

            });

        });

    });

    context('when the parameter is nullable', function () {

        beforeEach(function () {

            $this->parameter = new TypeHintedParameter($this->delegate->get(), ...[
                SomeClass::class,
                true,
            ]);

        });

        it('should implement ParameterInterface', function () {

            expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

        });

        describe('->name()', function () {

            it('should return the delegate name', function () {

                $this->delegate->name->returns('name');

                $test = $this->parameter->name();

                expect($test)->toEqual('name');

            });

        });

        describe('->hasTypeHint()', function () {

            it('should return true', function () {

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeTruthy();

            });

        });

        describe('->typeHint()', function () {

            it('should return the type hint', function () {

                $test = $this->parameter->typeHint();

                expect($test)->toEqual(new TypeHint(SomeClass::class, true));

            });

        });

        describe('->hasDefaultValue()', function () {

            it('should return true', function () {

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeTruthy();

            });

        });

        describe('->defaultValue()', function () {

            it('should return null', function () {

                $test = $this->parameter->defaultValue();

                expect($test)->toBeNull();

            });

        });

    });

});
