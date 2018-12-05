<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\ArgumentInterface;
use Quanta\DependencyInjection\Arguments\VariadicContainerEntry;

describe('VariadicContainerEntry', function () {

    beforeEach(function () {

        $this->argument = new VariadicContainerEntry('id');

    });

    it('should implement ArgumentInterface', function () {

        expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

    });

    describe('->hasValue()', function () {

        it('should return false', function () {

            $test = $this->argument->hasValue();

            expect($test)->toBeTruthy();

        });

    });

    describe('->values()', function () {

        context('when the container entry is an array', function () {

            it('should return the container entry', function () {

                $container = mock(ContainerInterface::class);

                $container->get->with('id')->returns([
                    'value1',
                    'value2',
                    'value3',
                ]);

                $test = $this->argument->values($container->get());

                expect($test)->toEqual(['value1', 'value2', 'value3']);

            });

        });

        context('when the container entry is not an array', function () {

            it('should throw a LogicException', function () {

                $container = mock(ContainerInterface::class);

                $container->get->with('id')->returns('value');

                $test = function () use ($container) {
                    $this->argument->values($container->get());
                };

                expect($test)->toThrow(new LogicException);

            });

        });

    });

});
