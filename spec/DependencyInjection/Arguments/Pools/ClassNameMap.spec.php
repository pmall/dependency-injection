<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ContainerEntry;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Arguments\VariadicContainerEntry;
use Quanta\DependencyInjection\Parameters\ParameterInterface;
use Quanta\DependencyInjection\Arguments\Pools\ClassNameMap;
use Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface;

describe('ClassNameMap', function () {

    beforeEach(function () {

        $this->pool = new ClassNameMap([
            SomeClass1::class => $this->value1 = mock(),
            SomeClass2::class => [$this->value2 = mock(), $this->value3 = mock(), $this->value4 = mock()],
            SomeClass4::class => $this->value5 = mock(),
        ], [
            SomeClass3::class => SomeClass3::class,
            SomeClass4::class => SomeClass4::class,
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

        context('when the given parameter is type hinted with a class name', function () {

            beforeEach(function () {

                $this->parameter->hasClassTypeHint->returns(true);

            });

            context('when the given parameter class name is in the value map', function () {

                context('when the given parameter is not variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(false);

                    });

                    context('when the value associated to the parameter is not an array', function () {

                        it('should return an Argument containing the value', function () {

                            $this->parameter->typeHint->returns(SomeClass1::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument($this->value1));

                        });

                    });

                    context('when the value associated to the parameter is an array', function () {

                        it('should return an Argument containing the array', function () {

                            $this->parameter->typeHint->returns(SomeClass2::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument([$this->value2, $this->value3, $this->value4]));

                        });

                    });

                });

                context('when the given parameter is variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(true);

                    });

                    context('when the value associated to the parameter is not an array', function () {

                        it('should return a placeholder', function () {

                            $this->parameter->typeHint->returns(SomeClass1::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toBeAnInstanceOf(Placeholder::class);

                        });

                    });

                    context('when the value associated to the parameter is an array', function () {

                        it('should return a VariadicArgument', function () {

                            $this->parameter->typeHint->returns(SomeClass2::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new VariadicArgument([$this->value2, $this->value3, $this->value4]));

                        });

                    });

                });

            });

            context('when the given parameter class name is in the alias map', function () {

                beforeEach(function () {

                    $this->parameter->typeHint->returns(SomeClass3::class);

                });

                context('when the given parameter is not variadic', function () {

                    it('should return a ContainerEntry', function () {

                        $this->parameter->isVariadic->returns(false);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new ContainerEntry(SomeClass3::class));

                    });

                });

                context('when the given parameter is variadic', function () {

                    it('should return a VariadicContainerEntry', function () {

                        $this->parameter->isVariadic->returns(true);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new VariadicContainerEntry(SomeClass3::class));

                    });

                });

            });

            context('when the parameter class name is both in the value map and in the alias map', function () {

                beforeEach(function () {

                    $this->parameter->typeHint->returns(SomeClass4::class);

                });

                context('when the given parameter is not variadic', function () {

                    it('should return a ContainerEntry', function () {

                        $this->parameter->isVariadic->returns(false);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new ContainerEntry(SomeClass4::class));

                    });

                });

                context('when the given parameter is variadic', function () {

                    it('should return a VariadicContainerEntry', function () {

                        $this->parameter->isVariadic->returns(true);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new VariadicContainerEntry(SomeClass4::class));

                    });

                });

            });

            context('when the parameter class name is not in the value map and not in the alias map', function () {

                it('should return a placeholder', function () {

                    $this->parameter->typeHint->returns(SomeClass5::class);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toBeAnInstanceOf(Placeholder::class);

                });

            });

        });

        context('when the given parameter is not type hinted with a class name', function () {

            it('should return a placeholder', function () {

                $this->parameter->hasClassTypeHint->returns(false);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toBeAnInstanceOf(Placeholder::class);

            });

        });

    });

});
