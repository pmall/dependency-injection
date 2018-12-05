<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Parameters\ParameterInterface;
use Quanta\DependencyInjection\Arguments\Pools\CompositeArgumentPool;
use Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface;

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

                $this->argument1->hasValue->returns(false);
                $this->argument2->hasValue->returns(true);
                $this->argument3->hasValue->returns(true);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toBe($this->argument2->get());

            });

        });

        context('when all argument pools return a placeholder', function () {

            beforeEach(function () {

                $this->argument1->hasValue->returns(false);
                $this->argument2->hasValue->returns(false);
                $this->argument3->hasValue->returns(false);

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

                it('should return placeholder', function () {

                    $this->parameter->hasDefaultValue->returns(false);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toBeAnInstanceOf(Placeholder::class);

                });

            });

        });

    });

});
