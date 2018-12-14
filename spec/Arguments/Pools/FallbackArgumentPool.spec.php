<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\FallbackArgumentPool;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Arguments\Pools\ContainerErrorMessage;

describe('FallbackArgumentPool', function () {

    beforeEach(function () {

        $this->pool = new FallbackArgumentPool;

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->argument()', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);
            $this->parameter = mock(ParameterInterface::class);

            $this->parameter->name->returns('x');

        });

        context('when the given parameter is variadic', function () {

            it('should return an empty variadic argument', function () {

                $this->parameter->isVariadic->returns(true);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toEqual(new VariadicArgument([]));

            });

        });

        context('when the given parameter is not variadic', function () {

            beforeEach(function () {

                $this->parameter->isVariadic->returns(false);

            });

            context('when the parameter has a class type hint', function () {

                beforeEach(function () {

                    $this->parameter->hasClassTypeHint->returns(true);
                    $this->parameter->typeHint->returns(SomeClass::class);

                });

                context('when a container entry is defined for this class name', function () {

                    beforeEach(function () {

                        $this->container->has->with(SomeClass::class)->returns(true);

                    });

                    context('when the container does not fail to retrieve the entry', function () {

                        it('should return a argument containing the container entry', function () {

                            $instance = new class {};

                            $this->container->get->with(SomeClass::class)->returns($instance);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument($instance));

                        });

                    });

                    context('when the container fails to retrieve the entry', function () {

                        it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                            $exception = mock(Throwable::class);

                            $this->container->get->with(SomeClass::class)->throws($exception);

                            $test = function () {
                                $this->pool->argument($this->container->get(), $this->parameter->get());
                            };

                            expect($test)->toThrow(new LogicException(
                                (string) new ContainerErrorMessage($this->parameter->get(), SomeClass::class),
                                0,
                                $exception->get()
                            ));

                        });

                    });

                });

                context('when no container entry is defined for this class name', function () {

                    context('when the given parameter has a default value', function () {

                        it('should return an argument containing the default value', function () {

                            $this->parameter->hasDefaultValue->returns(true);

                            $this->parameter->defaultValue->returns('value');

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument('value'));

                        });

                    });

                    context('when the given parameter does not have a default value', function () {

                        beforeEach(function () {

                            $this->parameter->hasDefaultValue->returns(false);

                        });

                        context('when the given parameter allows null', function () {

                            it('should return an argument containing null', function () {

                                $this->parameter->allowsNull->returns(true);

                                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                                expect($test)->toEqual(new Argument(null));

                            });

                        });

                        context('when the given parameter does not allow null', function () {

                            it('should return a placeholder', function () {

                                $this->parameter->allowsNull->returns(false);

                                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                                expect($test)->toEqual(new Placeholder);

                            });

                        });

                    });

                });

            });

            context('when the parameter does not have a class type hint', function () {

                beforeEach(function () {

                    $this->parameter->hasClassTypeHint->returns(false);

                });

                context('when the given parameter has a default value', function () {

                    it('should return an argument containing the default value', function () {

                        $this->parameter->hasDefaultValue->returns(true);

                        $this->parameter->defaultValue->returns('value');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Argument('value'));

                    });

                });

                context('when the given parameter does not have a default value', function () {

                    beforeEach(function () {

                        $this->parameter->hasDefaultValue->returns(false);

                    });

                    context('when the given parameter allows null', function () {

                        it('should return an argument containing null', function () {

                            $this->parameter->allowsNull->returns(true);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument(null));

                        });

                    });

                    context('when the given parameter does not allow null', function () {

                        it('should return a placeholder', function () {

                            $this->parameter->allowsNull->returns(false);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Placeholder);

                        });

                    });

                });

            });

        });

    });

});
