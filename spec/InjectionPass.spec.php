<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\InjectionPass;
use Quanta\DI\BoundCallable;
use Quanta\DI\InjectableCallableAdapter;
use Quanta\DI\InjectableCallableInterface;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('InjectionPass', function () {

    beforeEach(function () {

        $this->callable = mock(InjectableCallableInterface::class);
        $this->pool = mock(ArgumentPoolInterface::class);

        $this->pass = new InjectionPass(...[
            $this->callable->get(),
            $this->pool->get(),
        ]);

    });

    context('when the injectable callable has no parameter', function () {

        describe('->injected()', function () {

            it('should return a InjectableCallableAdapter from the injectable callable', function () {

                $this->container = mock(ContainerInterface::class);

                $this->callable->parameters->returns([]);

                $test = $this->pass->injected($this->container->get());

                $expected = new InjectableCallableAdapter($this->callable->get());

                expect($test)->toEqual($expected);

            });

        });

    });

    context('when the injectable callable has at least one parameter', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);
            $this->parameter3 = mock(ParameterInterface::class);

            $this->argument1 = mock(ArgumentInterface::class);
            $this->argument2 = mock(ArgumentInterface::class);
            $this->argument3 = mock(ArgumentInterface::class);

            $this->callable->parameters->returns([
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
                            new InjectableCallableAdapter($this->callable->get()),
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
