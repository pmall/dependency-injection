<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\ContainerEntries;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Arguments\Pools\ContainerErrorMessage;

describe('ContainerEntries', function () {

    beforeEach(function () {

        $this->pool = new ContainerEntries;

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->argument()', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);
            $this->parameter = mock(ParameterInterface::class);

        });

        context('when the given parameter is type hinted with a class name', function () {

            beforeEach(function () {

                $this->parameter->hasClassTypeHint->returns(true);
                $this->parameter->typeHint->returns(SomeClass::class);

            });

            context('when the parameter is not variadic', function () {

                beforeEach(function () {

                    $this->parameter->isVariadic->returns(false);

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

                    it('should return a placeholder', function () {

                        $this->container->has->with(SomeClass::class)->returns(false);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Placeholder);

                    });

                });

            });

            context('when the parameter is variadic', function () {

                it('should return a placeholder', function () {

                    $this->parameter->isVariadic->returns(true);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Placeholder);

                });

            });

        });

        context('when the given parameter is not type hinted with a class name', function () {

            it('should return a placeholder', function () {

                $this->parameter->hasClassTypeHint->returns(false);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toEqual(new Placeholder);

            });

        });

    });

});
