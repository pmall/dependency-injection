<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\TypeHint;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\TypeHintErrorMessage;
use Quanta\DI\Parameters\DefaultValueErrorMessage;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

describe('ReflectionParameterAdapter', function () {

    beforeEach(function () {

        $this->reflection = mock(ReflectionParameter::class);

        $this->reflection->getName->returns('name');

        $this->parameter = new ReflectionParameterAdapter($this->reflection->get());

    });

    it('should implement ParameterInterface', function () {

        expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

    });

    describe('->name()', function () {

        it('should return the parameter name', function () {

            $test = $this->parameter->name();

            expect($test)->toEqual('name');

        });

    });

    describe('->hasTypeHint()', function () {

        context('when the parameter type is not null', function () {

            beforeEach(function () {

                $this->type = mock(ReflectionNamedType::class);

                $this->reflection->getType->returns($this->type);

            });

            context('when the type is not built in', function () {

                it('should return true', function () {

                    $this->type->isBuiltIn->returns(false);

                    $test = $this->parameter->hasTypeHint();

                    expect($test)->toBeTruthy();

                });

            });

            context('when the type is built in', function () {

                it('should return false', function () {

                    $this->type->isBuiltIn->returns(true);

                    $test = $this->parameter->hasTypeHint();

                    expect($test)->toBeFalsy();

                });

            });

        });

        context('when the parameter type is null', function () {

            it('should return false', function () {

                $this->reflection->getType->returns(null);

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeFalsy();

            });

        });

    });

    describe('->typeHint()', function () {

        context('when the parameter type is not null', function () {

            beforeEach(function () {

                $this->type = mock(ReflectionNamedType::class);

                $this->type->getName->returns(SomeClass::class);

                $this->reflection->getType->returns($this->type);

            });

            context('when the type is not built in', function () {

                beforeEach(function () {

                    $this->type->isBuiltIn->returns(false);

                });

                context('when the parameter does not allow null', function () {

                    it('should return a non nullable type hint', function () {

                        $this->reflection->allowsNull->returns(false);

                        $test = $this->parameter->typeHint();

                        expect($test)->toEqual(new TypeHint(SomeClass::class, false));

                    });

                });

                context('when the parameter allows null', function () {

                    it('should return a nullable type hint', function () {

                        $this->reflection->allowsNull->returns(true);

                        $test = $this->parameter->typeHint();

                        expect($test)->toEqual(new TypeHint(SomeClass::class, true));

                    });

                });

            });

            context('when the type is built in', function () {

                it('should throw a LogicException', function () {

                    $this->type->isBuiltIn->returns(true);

                    expect([$this->parameter, 'typeHint'])->toThrow(new LogicException(
                        (string) new TypeHintErrorMessage($this->parameter)
                    ));

                });

            });

        });

        context('when the parameter type is null', function () {

            it('should throw a LogicException', function () {

                $this->reflection->getType->returns(null);

                expect([$this->parameter, 'typeHint'])->toThrow(new LogicException(
                    (string) new TypeHintErrorMessage($this->parameter)
                ));

            });

        });

    });

    describe('->hasDefaultValue()', function () {

        context('when the parameter has a default value', function () {

            it('should return true', function () {

                $this->reflection->isDefaultValueAvailable->returns(true);

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeTruthy();

            });

        });

        context('when the parameter does not have a default value', function () {

            beforeEach(function () {

                $this->reflection->isDefaultValueAvailable->returns(false);

            });

            context('when the parameter allows null', function () {

                it('should return true', function () {

                    $this->reflection->allowsNull->returns(true);

                    $test = $this->parameter->hasDefaultValue();

                    expect($test)->toBeTruthy();

                });

            });

            context('when the parameter does not allow null', function () {

                it('should return false', function () {

                    $this->reflection->allowsNull->returns(false);

                    $test = $this->parameter->hasDefaultValue();

                    expect($test)->toBeFalsy();

                });

            });

        });

    });

    describe('->defaultValue()', function () {

        context('when the parameter has a default value', function () {

            it('should return the default value', function () {

                $this->reflection->isDefaultValueAvailable->returns(true);
                $this->reflection->getDefaultValue->returns('default');

                $test = $this->parameter->defaultValue();

                expect($test)->toEqual('default');

            });

        });

        context('when the parameter does not have a default value', function () {

            beforeEach(function () {

                $this->reflection->isDefaultValueAvailable->returns(false);

            });

            context('when the parameter allows null', function () {

                it('should return null', function () {

                    $this->reflection->allowsNull->returns(true);

                    $test = $this->parameter->defaultValue();

                    expect($test)->toBeNull();

                });

            });

            context('when the parameter does not allow null', function () {

                it('should throw a LogicException', function () {

                    $this->reflection->allowsNull->returns(false);

                    expect([$this->parameter, 'defaultValue'])->toThrow(new LogicException(
                        (string) new DefaultValueErrorMessage($this->parameter)
                    ));

                });

            });

        });

    });

});
