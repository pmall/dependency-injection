<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\UnboundArgument;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\CompositeArgumentPool;
use Quanta\DI\Parameters\ParameterInterface;

describe('CompositeArgumentPool', function () {

    context('when there is no argument pool', function () {

        beforeEach(function () {

            $this->pool = new CompositeArgumentPool;

        });

        it('should implement ArgumentPoolInterface', function () {

            expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

        });

        describe('->argument()', function () {

            it('should return an unbound argument', function () {

                $parameter = mock(ParameterInterface::class);

                $test = $this->pool->argument($parameter->get());

                expect($test)->toEqual(new UnboundArgument);

            });

        });

    });

    context('when there is at least one argument pool', function () {

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

                $this->parameter = mock(ParameterInterface::class);

            });

            context('when an argument pool returns a bound argument for the given parameter', function () {

                it('should return the first bound argument', function () {

                    $argument1 = new UnboundArgument;
                    $argument2 = new Argument('argument2');
                    $argument3 = new Argument('argument3');

                    $this->pool1->argument->with($this->parameter)->returns($argument1);
                    $this->pool2->argument->with($this->parameter)->returns($argument2);
                    $this->pool3->argument->with($this->parameter)->returns($argument3);

                    $test = $this->pool->argument($this->parameter->get());

                    expect($test)->toBe($argument2);

                });

            });

            context('when no argument pool returns a bound argument for the given parameter', function () {

                it('should return the last unbound argument', function () {

                    $argument1 = new UnboundArgument;
                    $argument2 = new UnboundArgument;
                    $argument3 = new UnboundArgument;

                    $this->pool1->argument->with($this->parameter)->returns($argument1);
                    $this->pool2->argument->with($this->parameter)->returns($argument2);
                    $this->pool3->argument->with($this->parameter)->returns($argument3);

                    $test = $this->pool->argument($this->parameter->get());

                    expect($test)->toBe($argument3);

                });

            });

        });

    });

});
