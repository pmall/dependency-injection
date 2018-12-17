<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\OptionalParameter;
use Quanta\DI\Parameters\ParameterInterface;

describe('OptionalParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(ParameterInterface::class);

        $this->parameter = new OptionalParameter($this->delegate->get(), 'default');

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

        it('should return the default value', function () {

            $test = $this->parameter->defaultValue();

            expect($test)->toEqual('default');

        });

    });

    describe('->hasDefaultValue()', function () {

        it('should return true', function () {

            $test = $this->parameter->hasDefaultValue();

            expect($test)->toBeTruthy();

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
