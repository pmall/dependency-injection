<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;

describe('Placeholder', function () {

    beforeEach(function () {

        $this->argument = new Placeholder;

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

        it('should return an empty array', function () {

            $container = mock(ContainerInterface::class);

            $test = $this->argument->values($container->get());

            expect($test)->toEqual([]);

        });

    });

});
