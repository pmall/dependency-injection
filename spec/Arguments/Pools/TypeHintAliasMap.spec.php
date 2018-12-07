<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\TypeHintAliasMap;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Arguments\Pools\ContainerErrorMessage;

describe('TypeHintAliasMap', function () {

    beforeEach(function () {

        $this->pool = new TypeHintAliasMap([
            SomeClass::class => 'id',
        ]);

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->argument()', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);
            $this->parameter = mock(ParameterInterface::class);

        });

        context('when the parameter has a class type hint', function () {

            beforeEach(function () {

                $this->parameter->hasClassTypeHint->returns(true);

                $this->parameter->typeHint->returns(SomeClass::class);

            });

            context('when the given parameter class name is in the map', function () {

                context('when the container does not fail to retrieve the entry', function () {

                    context('when the given parameter is not variadic', function () {

                        beforeEach(function () {

                            $this->parameter->isVariadic->returns(false);

                        });

                        context('when the container entry associated to the parameter is not an array', function () {

                            it('should return an Argument containing the container entry', function () {

                                $instance = new class {};

                                $this->container->get->with('id')->returns($instance);

                                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                                expect($test)->toEqual(new Argument($instance));

                            });

                        });

                        context('when the container entry associated to the parameter is an array', function () {

                            it('should return an Argument containing the container entry', function () {

                                $instances = [new class {}, new class {}, new class {}];

                                $this->container->get->with('id')->returns($instances);

                                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                                expect($test)->toEqual(new Argument($instances));

                            });

                        });

                    });

                    context('when the given parameter is variadic', function () {

                        beforeEach(function () {

                            $this->parameter->isVariadic->returns(true);

                        });

                        context('when the value associated to the parameter is an array', function () {

                            it('should return a VariadicArgument containing the value', function () {

                                $instances = [new class {}, new class {}, new class {}];

                                $this->container->get->with('id')->returns($instances);

                                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                                expect($test)->toEqual(new VariadicArgument($instances));

                            });

                        });

                        context('when the value associated to the parameter is not an array', function () {

                            it('it should throw a logic exception', function () {

                                $instance = new class {};

                                $this->container->get->with('id')->returns($instance);

                                $test = function () {
                                    $this->pool->argument($this->container->get(), $this->parameter->get());
                                };

                                expect($test)->toThrow(new LogicException);

                            });

                        });

                    });

                });

                context('when the container fails to retrieve the entry', function () {

                    it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                        $exception = mock(Throwable::class);

                        $this->container->get->with('id')->throws($exception);

                        $test = function () {
                            $this->pool->argument($this->container->get(), $this->parameter->get());
                        };

                        expect($test)->toThrow(new LogicException(
                            (string) new ContainerErrorMessage($this->parameter->get(), 'id'),
                            0,
                            $exception->get()
                        ));

                    });

                });

            });

            context('when the given parameter class name is not in the map', function () {

                it('should return a placeholder', function () {

                    $this->parameter->typeHint->returns(SomeClass1::class);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Placeholder);

                });

            });

        });

        context('when the parameter does not have a class type hint', function () {

            it('should return a placheolder', function () {

                $this->parameter->hasClassTypeHint->returns(false);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toEqual(new Placeholder);

            });

        });

    });

});
