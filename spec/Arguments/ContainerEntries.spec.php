<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ContainerEntries;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\ContainerErrorMessage;

use Quanta\DI\Parameters\ParameterInterface;

describe('ContainerEntries', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

        $this->pool = new ContainerEntries($this->container->get());

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->arguments()', function () {

        beforeEach(function () {

            $this->parameter = mock(ParameterInterface::class);

            $this->parameter->name->returns('$name');

        });

        context('when the given parameter is variadic', function () {

            it('should return an empty array', function () {

                $this->parameter->isVariadic->returns(true);

                $test = $this->pool->arguments($this->parameter->get());

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(0);

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

                        it('should return an array containing the container entry', function () {

                            $instance = new class {};

                            $this->container->get->with(SomeClass::class)->returns($instance);

                            $test = $this->pool->arguments($this->parameter->get());

                            expect($test)->toBeAn('array');
                            expect($test)->toHaveLength(1);
                            expect($test[0])->toBe($instance);

                        });

                    });

                    context('when the container fails to retrieve the entry', function () {

                        it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                            $exception = mock(Throwable::class);

                            $this->container->get->with(SomeClass::class)->throws($exception);

                            $test = function () {
                                $this->pool->arguments($this->parameter->get());
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

                    it('should return an empty array', function () {

                        $this->container->has->with(SomeClass::class)->returns(false);

                        $test = $this->pool->arguments($this->parameter->get());

                        expect($test)->toBeAn('array');
                        expect($test)->toHaveLength(0);

                    });

                });

            });

        });

    });

});
