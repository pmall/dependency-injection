<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Placeholder;
use Quanta\DI\Arguments\ArgumentInterface;

describe('Placeholder', function () {

    beforeEach(function () {

        $this->argument = new Placeholder;

    });

    it('should implement ArgumentInterface', function () {

        expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

    });

    describe('->values()', function () {

        it('should return an empty array', function () {

            $container = mock(ContainerInterface::class);

            $test = $this->argument->values($container->get());

            expect($test)->toEqual([]);

        });

    });

});
