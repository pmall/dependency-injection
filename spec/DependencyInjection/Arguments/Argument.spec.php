<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;

describe('Argument', function () {

    beforeEach(function () {

        $this->argument = new Argument('value');

    });

    it('should implement ArgumentInterface', function () {

        expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

    });

    describe('->hasValue()', function () {

        it('should return false', function () {

            $test = $this->argument->hasValue();

            expect($test)->toBeFalsy();

        });

    });

    describe('->values()', function () {

        it('should return an array containing the value', function () {

            $container = mock(ContainerInterface::class);

            $test = $this->argument->values($container->get());

            expect($test)->toEqual(['value']);

        });

    });

});
