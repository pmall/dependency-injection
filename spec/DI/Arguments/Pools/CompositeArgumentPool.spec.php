<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\CompositeArgumentPool;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;

describe('CompositeArgumentPool', function () {

    beforeEach(function () {

        $this->pool1 = mock(ArgumentPoolInterface::class);
        $this->pool2 = mock(ArgumentPoolInterface::class);
        $this->pool3 = mock(ArgumentPoolInterface::class);

        $this->pool = new CompositeArgumentPool(...[
            $this->pool1->get(),
            $this->pool2->get(),
            $this->pool3->get(),
        ]);

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->argument()', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);
            $this->parameter = mock(ParameterInterface::class);

            $this->argument1 = mock(ArgumentInterface::class);
            $this->argument2 = mock(ArgumentInterface::class);
            $this->argument3 = mock(ArgumentInterface::class);

            $this->pool1->argument
                ->with($this->container, $this->parameter)
                ->returns($this->argument1);

            $this->pool2->argument
                ->with($this->container, $this->parameter)
                ->returns($this->argument2);

            $this->pool3->argument
                ->with($this->container, $this->parameter)
                ->returns($this->argument3);

        });

        context('when at least one argument pool does not return a placeholder', function () {

            it('should return the first non placeholder argument', function () {

                $this->argument1->isPlaceholder->returns(true);
                $this->argument2->isPlaceholder->returns(false);
                $this->argument3->isPlaceholder->returns(false);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toBe($this->argument2->get());

            });

        });

        context('when all argument pools return a placeholder', function () {

            beforeEach(function () {

                $this->argument1->isPlaceholder->returns(true);
                $this->argument2->isPlaceholder->returns(true);
                $this->argument3->isPlaceholder->returns(true);

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
