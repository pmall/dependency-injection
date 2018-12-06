<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

describe('ReflectionParameterAdapter', function () {

    beforeEach(function () {

        $this->delegate = mock(ReflectionParameter::class);

        $this->delegate->getName->returns('x');

        $this->parameter = new ReflectionParameterAdapter($this->delegate->get());

    });

    it('should implement ParameterInterface', function () {

        expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

    });

    describe('->name()', function () {

        it('should return the parameter name', function () {

            $test = $this->parameter->name();

            expect($test)->toEqual('x');

        });

    });

    describe('->typeHint()', function () {

        context('when the parameter type is not null', function () {

            it('should return the type as a string', function () {

                $type = mock(ReflectionType::class);

                $type->__toString->returns('type');

                $this->delegate->getType->returns($type);

                $test = $this->parameter->typeHint();

                expect($test)->toEqual('type');

            });

        });

        context('when the parameter type is null', function () {

            it('should throw a LogicException', function () {

                $this->delegate->getType->returns(null);

                $test = [$this->parameter, 'typeHint'];

                expect($test)->toThrow(new LogicException);

            });

        });

    });

    describe('->hasTypeHint()', function () {

        context('when the parameter has a type hint', function () {

            it('should return true', function () {

                $this->delegate->hasType->returns(true);

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeTruthy();

            });

        });

        context('when the parameter does not have a type hint', function () {

            it('should return false', function () {

                $this->delegate->hasType->returns(false);

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->hasClassTypeHint()', function () {

        context('when the parameter type is not null', function () {

            context('when the type is built in', function () {

                it('should return false', function () {

                    $type = mock(ReflectionType::class);

                    $type->isBuiltIn->returns(true);

                    $this->delegate->getType->returns($type);

                    $test = $this->parameter->hasClassTypeHint();

                    expect($test)->toBeFalsy();

                });

            });

            context('when the type is not built in', function () {

                it('should return true', function () {

                    $type = mock(ReflectionType::class);

                    $type->isBuiltIn->returns(false);

                    $this->delegate->getType->returns($type);

                    $test = $this->parameter->hasClassTypeHint();

                    expect($test)->toBeTruthy();

                });

            });

        });

        context('when the parameter type is null', function () {

            it('should return false', function () {

                $this->delegate->getType->returns(null);

                $test = $this->parameter->hasClassTypeHint();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->defaultValue()', function () {

        context('when the parameter ->getDefaultValue() does not throw an exception', function () {

            it('should return the default value', function () {

                $this->delegate->getDefaultValue->returns('default');

                $test = $this->parameter->defaultValue();

                expect($test)->toEqual('default');

            });

        });

        context('when the parameter ->getDefaultValue() throws a ReflectionException', function () {

            it('should throw a LogicException', function () {

                $this->delegate->getDefaultValue->throws(new ReflectionException);

                $test = [$this->parameter, 'defaultValue'];

                expect($test)->toThrow(new LogicException);

            });

        });

    });

    describe('->hasDefaultValue()', function () {

        context('when the parameter has a default value', function () {

            it('should return true', function () {

                $this->delegate->isDefaultValueAvailable->returns(true);

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeTruthy();

            });

        });

        context('when the parameter does not have a default value', function () {

            it('should return false', function () {

                $this->delegate->isDefaultValueAvailable->returns(false);

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->allowsNull()', function () {

        context('when the parameter allows null', function () {

            it('should return true', function () {

                $this->delegate->allowsNull->returns(true);

                $test = $this->parameter->allowsNull();

                expect($test)->toBeTruthy();

            });

        });

        context('when the parameter does not allow null', function () {

            it('should return false', function () {

                $this->delegate->allowsNull->returns(false);

                $test = $this->parameter->allowsNull();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->isVariadic()', function () {

        context('when the parameter is variadic', function () {

            it('should return true', function () {

                $this->delegate->isVariadic->returns(true);

                $test = $this->parameter->isVariadic();

                expect($test)->toBeTruthy();

            });

        });

        context('when the parameter is not variadic', function () {

            it('should return false', function () {

                $this->delegate->isVariadic->returns(false);

                $test = $this->parameter->isVariadic();

                expect($test)->toBeFalsy();

            });

        });

    });

});
