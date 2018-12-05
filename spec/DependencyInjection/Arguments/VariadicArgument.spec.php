<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;

describe('VariadicArgument', function () {

    beforeEach(function () {

        $this->argument = new VariadicArgument(['value1', 'value2', 'value3']);

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

        it('should return the array of values', function () {

            $container = mock(ContainerInterface::class);

            $test = $this->argument->values($container->get());

            expect($test)->toEqual(['value1', 'value2', 'value3']);

        });

    });

});
