<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\NullableParameter;
use Quanta\DI\Parameters\ParameterInterface;

describe('NullableParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(ParameterInterface::class);

        $this->parameter = new NullableParameter($this->delegate->get());

    });

    it('should implement ParameterInterface', function () {

        expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

    });

    describe('->name()', function () {

        it('should return the delegate name', function () {

            $this->delegate->name->returns('$name');

            $test = $this->parameter->name();

            expect($test)->toEqual('$name');

        });

    });

    describe('->typeHint()', function () {

        it('should return the delegate type hint', function () {

            $this->delegate->typeHint->returns('typehint');

            $test = $this->parameter->typeHint();

            expect($test)->toEqual('typehint');

        });

    });

    describe('->hasTypeHint()', function () {

        context('when the delegate hasTypeHint returns true', function () {

            it('should return true', function () {

                $this->delegate->hasTypeHint->returns(true);

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeTruthy();

            });

        });

        context('when the delegate hasTypeHint returns false', function () {

            it('should return false', function () {

                $this->delegate->hasTypeHint->returns(false);

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->hasClassTypeHint()', function () {

        context('when the delegate hasClassTypeHint returns true', function () {

            it('should return true', function () {

                $this->delegate->hasClassTypeHint->returns(true);

                $test = $this->parameter->hasClassTypeHint();

                expect($test)->toBeTruthy();

            });

        });

        context('when the delegate hasClassTypeHint returns false', function () {

            it('should return false', function () {

                $this->delegate->hasClassTypeHint->returns(false);

                $test = $this->parameter->hasClassTypeHint();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->defaultValue()', function () {

        it('should return the delegate default value', function () {

            $this->delegate->typeHint->returns('default');

            $test = $this->parameter->typeHint();

            expect($test)->toEqual('default');

        });

    });

    describe('->hasDefaultValue()', function () {

        context('when the delegate hasDefaultValue returns true', function () {

            it('should return true', function () {

                $this->delegate->hasDefaultValue->returns(true);

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeTruthy();

            });

        });

        context('when the delegate hasDefaultValue returns false', function () {

            it('should return false', function () {

                $this->delegate->hasDefaultValue->returns(false);

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->allowsNull()', function () {

        it('should return true', function () {

            $test = $this->parameter->allowsNull();

            expect($test)->toBeTruthy();

        });

    });

    describe('->isVariadic()', function () {

        context('when the delegate isVariadic returns true', function () {

            it('should return true', function () {

                $this->delegate->isVariadic->returns(true);

                $test = $this->parameter->isVariadic();

                expect($test)->toBeTruthy();

            });

        });

        context('when the delegate isVariadic returns false', function () {

            it('should return false', function () {

                $this->delegate->isVariadic->returns(false);

                $test = $this->parameter->isVariadic();

                expect($test)->toBeFalsy();

            });

        });

    });

});
