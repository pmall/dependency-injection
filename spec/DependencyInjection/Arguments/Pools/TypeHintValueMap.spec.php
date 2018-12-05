<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Parameters\ParameterInterface;
use Quanta\DependencyInjection\Arguments\Pools\TypeHintValueMap;
use Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface;

describe('TypeHintValueMap', function () {

    beforeEach(function () {

        $this->instance = new class {};
        $this->instances = [new class {}, new class {}, new class {}];

        $this->pool = new TypeHintValueMap([
            SomeClass1::class => $this->instance,
            SomeClass2::class => $this->instances,
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

            });

            context('when the given parameter class name is in the map', function () {

                context('when the given parameter is not variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(false);

                    });

                    context('when the value associated to the parameter is not an array', function () {

                        it('should return an Argument containing the value', function () {

                            $this->parameter->typeHint->returns(SomeClass1::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument($this->instance));

                        });

                    });

                    context('when the value associated to the parameter is an array', function () {

                        it('should return an Argument containing the array', function () {

                            $this->parameter->typeHint->returns(SomeClass2::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new Argument($this->instances));

                        });

                    });

                });

                context('when the given parameter is variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(true);

                    });

                    context('when the value associated to the parameter is an array', function () {

                        it('should return a VariadicArgument containing the value', function () {

                            $this->parameter->typeHint->returns(SomeClass2::class);

                            $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                            expect($test)->toEqual(new VariadicArgument($this->instances));

                        });

                    });

                    context('when the value associated to the parameter is not an array', function () {

                        it('it should throw a logic exception', function () {

                            $this->parameter->typeHint->returns(SomeClass1::class);

                            $test = function () {
                                $this->pool->argument($this->container->get(), $this->parameter->get());
                            };

                            expect($test)->toThrow(new LogicException);

                        });

                    });

                });

            });

            context('when the given parameter class name is not in the map', function () {

                it('should return a placeholder', function () {

                    $this->parameter->typeHint->returns(SomeClass3::class);

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
