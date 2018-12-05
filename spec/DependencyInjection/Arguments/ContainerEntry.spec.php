<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\ContainerEntry;
use Quanta\DependencyInjection\Arguments\ArgumentInterface;

describe('ContainerEntry', function () {

    beforeEach(function () {

        $this->argument = new ContainerEntry('id');

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

        it('should return an array containing the container entry', function () {

            $container = mock(ContainerInterface::class);

            $container->get->with('id')->returns('value');

            $test = $this->argument->values($container->get());

            expect($test)->toEqual(['value']);

        });

    });

});
