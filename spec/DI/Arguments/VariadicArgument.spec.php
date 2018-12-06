<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\VariadicArgument;
use Quanta\DI\Arguments\ArgumentInterface;

describe('VariadicArgument', function () {

    beforeEach(function () {

        $this->argument = new VariadicArgument(['value1', 'value2', 'value3']);

    });

    it('should implement ArgumentInterface', function () {

        expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

    });

    describe('->values()', function () {

        it('should return the array of values', function () {

            $container = mock(ContainerInterface::class);

            $test = $this->argument->values($container->get());

            expect($test)->toEqual(['value1', 'value2', 'value3']);

        });

    });

});
