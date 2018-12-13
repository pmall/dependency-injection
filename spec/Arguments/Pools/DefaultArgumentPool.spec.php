<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\Pools\DefaultArgumentPool;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\Exceptions\ArrayArgumentTypeErrorMessage;

describe('CompositeArgumentPool', function () {

    context('when all values of the given array of aliases are strings', function () {

        beforeEach(function () {

            $this->pool = new DefaultArgumentPool([
                '$p1' => '@' . SomeClass1::class,
                '$p2' => 'value1',
                AliasedInsterface1::class => '@' . SomeClass2::class,
                AliasedInsterface2::class => 'value2',
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

            context('when the parameter name is an option key', function () {

                context('when the associated value starts with @', function () {

                    context('when the container does not fail to retrieve the entry associated with the alias', function () {

                        it('should return an argument containing the container entry associated with the alias', function () {

                            $instance = new class {};

                            $this->container->get->with(SomeClass1::class)->returns($instance);
                            $this->parameter->name->returns('p1');

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument($instance));

                        });

                    });

                    context('when the container fails to retrieve the entry associated with the alias', function () {

                        it('should throw a LogicException', function () {

                            $exception = mock(Throwable::class);

                            $this->container->get->with(SomeClass1::class)->throws($exception);
                            $this->parameter->name->returns('p1');

                            $test = function () {
                                $this->pool->argument($this->container->get(), $this->parameter->get());
                            };

                            expect($test)->toThrow(new LogicException('', 0, $exception->get()));

                        });

                    });

                });

                context('when the associated value does not start with @', function () {

                    it('should return an argument containing the associated value', function () {

                        $this->parameter->name->returns('p2');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Argument('value1'));

                    });

                });

            });

            context('when the parameter name is not an option key', function () {

                beforeEach(function () {

                    $this->parameter->name->returns('p3');

                });

                context('when the parameter class name is an option key', function () {

                    context('when the associated value starts with @', function () {

                        context('when the container does not fail to retrieve the entry associated with the alias', function () {

                            it('should return an argument containing the container entry associated with the alias', function () {

                                $instance = new class {};

                                $this->container->get->with(SomeClass2::class)->returns($instance);
                                $this->parameter->hasClassTypeHint->returns(true);
                                $this->parameter->typeHint->returns(AliasedInsterface1::class);

                                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                                expect($test)->toEqual(new Argument($instance));

                            });

                        });

                        context('when the container fails to retrieve the entry associated with the alias', function () {

                            it('should throw a LogicException', function () {

                                $exception = mock(Throwable::class);

                                $this->container->get->with(SomeClass2::class)->throws($exception);
                                $this->parameter->hasClassTypeHint->returns(true);
                                $this->parameter->typeHint->returns(AliasedInsterface1::class);

                                $test = function () {
                                    $this->pool->argument($this->container->get(), $this->parameter->get());
                                };

                                expect($test)->toThrow(new LogicException('', 0, $exception->get()));

                            });

                        });

                    });

                    context('when the associated value does not start with @', function () {

                        it('should return an argument containing the associated value', function () {

                            $this->parameter->hasClassTypeHint->returns(true);
                            $this->parameter->typeHint->returns(AliasedInsterface2::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument('value2'));

                        });

                    });

                });

                context('when the parameter class name is not an option key', function () {

                    beforeEach(function () {

                        $this->parameter->hasClassTypeHint->returns(true);
                        $this->parameter->typeHint->returns(AliasedInsterface3::class);

                    });

                    context('when the container has an entry associated to the parameter class name', function () {

                        it('should return an argument containing the container entry associated with the parameter class name', function () {

                            $instance = new class {};

                            $this->container->has->with(AliasedInsterface3::class)->returns(true);
                            $this->container->get->with(AliasedInsterface3::class)->returns($instance);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument($instance));

                        });

                    });

                    context('when the container does not have an entry associated to the parameter class name', function () {

                        it('should return a placeholder', function () {

                            $this->container->has->with(AliasedInsterface3::class)->returns(false);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Placeholder);

                        });

                    });

                });

            });

        });

    });

});
