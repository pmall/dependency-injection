<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\TypeHintedParameter;

describe('TypeHintedParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(ParameterInterface::class);

        $this->parameter = new TypeHintedParameter($this->delegate->get(), SomeClass::class);

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

        it('should return the type hint', function () {

            $test = $this->parameter->typeHint();

            expect($test)->toEqual(SomeClass::class);

        });

    });

    describe('->hasTypeHint()', function () {

        it('should return true', function () {

            $test = $this->parameter->hasTypeHint();

            expect($test)->toBeTruthy();

        });

    });

    describe('->hasClassTypeHint()', function () {

        it('should return true', function () {

            $test = $this->parameter->hasClassTypeHint();

            expect($test)->toBeTruthy();

        });

    });

    describe('->defaultValue()', function () {

        it('should return the delegate default value', function () {

            $this->delegate->defaultValue->returns('default');

            $test = $this->parameter->defaultValue();

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

        context('when the delegate allowsNull returns true', function () {

            it('should return true', function () {

                $this->delegate->allowsNull->returns(true);

                $test = $this->parameter->allowsNull();

                expect($test)->toBeTruthy();

            });

        });

        context('when the delegate allowsNull returns false', function () {

            it('should return false', function () {

                $this->delegate->allowsNull->returns(false);

                $test = $this->parameter->allowsNull();

                expect($test)->toBeFalsy();

            });

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
