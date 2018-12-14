<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Arguments\Pools\CompositeArgumentPool;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;

describe('CompositeArgumentPool', function () {

    it('should implement ArgumentPoolInterface', function () {

        expect(new CompositeArgumentPool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->argument()', function () {

        beforeEach(function () {

            $this->container = mock(ContainerInterface::class);
            $this->parameter = mock(ParameterInterface::class);

        });

        context('when there is no argument pool', function () {

            it('should return a placeholder', function () {

                $pool = new CompositeArgumentPool;

                $test = $pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toEqual(new Placeholder);

            });

        });

        context('when there is at least one argument pool', function () {

            beforeEach(function () {

                $this->pool1 = mock(ArgumentPoolInterface::class);
                $this->pool2 = mock(ArgumentPoolInterface::class);
                $this->pool3 = mock(ArgumentPoolInterface::class);

                $this->argument1 = mock(ArgumentInterface::class);
                $this->argument2 = mock(ArgumentInterface::class);
                $this->argument3 = mock(ArgumentInterface::class);

                $this->pool = new CompositeArgumentPool(...[
                    $this->pool1->get(),
                    $this->pool2->get(),
                    $this->pool3->get(),
                ]);

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

                it('should return the last placeholder', function () {

                    $this->argument1->isPlaceholder->returns(true);
                    $this->argument2->isPlaceholder->returns(true);
                    $this->argument3->isPlaceholder->returns(true);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toBe($this->argument3->get());

                });

            });

        });

    });

});
