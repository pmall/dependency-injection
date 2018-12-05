<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ContainerEntry;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Arguments\VariadicContainerEntry;
use Quanta\DependencyInjection\Parameters\ParameterInterface;
use Quanta\DependencyInjection\Arguments\Pools\NameMap;
use Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface;

describe('NameMap', function () {

    beforeEach(function () {

        $this->pool = new NameMap([
            'name1' => 'value',
            'name2' => ['value1', 'value2', 'value3'],
            'name4' => 'value',
        ], [
            'name3' => SomeClass::class,
            'name4' => SomeClass::class,
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

        context('when the given parameter name is in the value map', function () {

            context('when the given parameter is not variadic', function () {

                beforeEach(function () {

                    $this->parameter->isVariadic->returns(false);

                });

                context('when the value associated to the parameter is not an array', function () {

                    it('should return an Argument containing the value', function () {

                        $this->parameter->name->returns('name1');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Argument('value'));

                    });

                });

                context('when the value associated to the parameter is an array', function () {

                    it('should return an Argument containing the array', function () {

                        $this->parameter->name->returns('name2');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Argument(['value1', 'value2', 'value3']));

                    });

                });

            });

            context('when the given parameter is variadic', function () {

                beforeEach(function () {

                    $this->parameter->isVariadic->returns(true);

                });

                context('when the value associated to the parameter is not an array', function () {

                    it('should return a placeholder', function () {

                        $this->parameter->name->returns('name1');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toBeAnInstanceOf(Placeholder::class);

                    });

                });

                context('when the value associated to the parameter is an array', function () {

                    it('should return a VariadicArgument', function () {

                        $this->parameter->name->returns('name2');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new VariadicArgument(['value1', 'value2', 'value3']));

                    });

                });

            });

        });

        context('when the given parameter name is in the alias map', function () {

            beforeEach(function () {

                $this->parameter->name->returns('name3');

            });

            context('when the given parameter is not variadic', function () {

                it('should return a ContainerEntry', function () {

                    $this->parameter->isVariadic->returns(false);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new ContainerEntry(SomeClass::class));

                });

            });

            context('when the given parameter is variadic', function () {

                it('should return a VariadicContainerEntry', function () {

                    $this->parameter->isVariadic->returns(true);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new VariadicContainerEntry(SomeClass::class));

                });

            });

        });

        context('when the parameter name is both in the value map and in the alias map', function () {

            beforeEach(function () {

                $this->parameter->name->returns('name4');

            });

            context('when the given parameter is not variadic', function () {

                it('should return a ContainerEntry', function () {

                    $this->parameter->isVariadic->returns(false);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new ContainerEntry(SomeClass::class));

                });

            });

            context('when the given parameter is variadic', function () {

                it('should return a VariadicContainerEntry', function () {

                    $this->parameter->isVariadic->returns(true);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new VariadicContainerEntry(SomeClass::class));

                });

            });

        });

        context('when the parameter name is not in the value map and not in the alias map', function () {

            it('should return a placeholder', function () {

                $this->parameter->name->returns('name5');

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toBeAnInstanceOf(Placeholder::class);

            });

        });

    });

});
