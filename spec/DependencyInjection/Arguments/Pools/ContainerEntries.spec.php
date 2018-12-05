<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ContainerEntry;
use Quanta\DependencyInjection\Parameters\ParameterInterface;
use Quanta\DependencyInjection\Arguments\Pools\ContainerEntries;
use Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface;

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

                    it('should return a ContainerEntry', function () {

                        $this->container->has->with(SomeClass::class)->returns(true);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new ContainerEntry(SomeClass::class));

                    });

                });

                context('when no container entry is defined for this class name', function () {

                    it('should return a placeholder', function () {

                        $this->container->has->with(SomeClass::class)->returns(false);

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toBeAnInstanceOf(Placeholder::class);

                    });

                });

            });

            context('when the parameter is variadic', function () {

                it('should return a placeholder', function () {

                    $this->parameter->isVariadic->returns(true);

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
