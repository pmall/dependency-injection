<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\InjectionPass;
use Quanta\DI\BoundCallable;
use Quanta\DI\CallableAdapter;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ParameterCollectionInterface;

describe('InjectionPass', function () {

    beforeEach(function () {

        $this->callable = function () {};
        $this->pool = mock(ArgumentPoolInterface::class);
        $this->collection = mock(ParameterCollectionInterface::class);

        $this->pass = new InjectionPass(...[
            $this->callable,
            $this->pool->get(),
            $this->collection->get(),
        ]);

    });

    context('when the parameter collection has no parameter', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);

            $this->collection->parameters->returns([]);

        });

        describe('->injected()', function () {

            it('should return a CallableAdapter from the callable', function () {

                $test = $this->pass->injected($this->container->get());

                expect($test)->toEqual(new CallableAdapter($this->callable));

            });

        });

    });

    context('when there is at least one parameters', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);
            $this->parameter3 = mock(ParameterInterface::class);

            $this->argument1 = mock(ArgumentInterface::class);
            $this->argument2 = mock(ArgumentInterface::class);
            $this->argument3 = mock(ArgumentInterface::class);

            $this->collection->parameters->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
            ]);

            $this->pool->argument
                ->with($this->container, $this->parameter1)
                ->returns($this->argument1);

            $this->pool->argument
                ->with($this->container, $this->parameter2)
                ->returns($this->argument2);

            $this->pool->argument
                ->with($this->container, $this->parameter3)
                ->returns($this->argument3);

        });

        describe('->injected()', function () {

            it('should return a BoundCallable binding the callable to the arguments provided by the argument pool', function () {

                $test = $this->pass->injected($this->container->get());

                $expected = new BoundCallable(
                    new BoundCallable(
                        new BoundCallable(
                            new CallableAdapter($this->callable),
                            $this->argument1->get()
                        ),
                        $this->argument2->get()
                    ),
                    $this->argument3->get()
                );

                expect($test)->toEqual($expected);

            });

        });

    });

});
