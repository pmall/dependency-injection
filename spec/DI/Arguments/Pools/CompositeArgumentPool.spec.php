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

        context('when at least one argument pool returns an argument with values', function () {

            it('should return the first argument with values', function () {

                $this->argument1->values->returns([]);
                $this->argument2->values->returns(['value']);
                $this->argument3->values->returns(['value']);

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toBe($this->argument2->get());

            });

        });

        context('when no argument pool return an argument with values', function () {

            beforeEach(function () {

                $this->argument1->values->returns([]);
                $this->argument2->values->returns([]);
                $this->argument3->values->returns([]);

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

                it('should return a placeholder', function () {

                    $this->parameter->hasDefaultValue->returns(false);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Placeholder);

                });

            });

        });

    });

});
