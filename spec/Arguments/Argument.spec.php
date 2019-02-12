<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\PA\CallableWithArgument;
use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\ArgumentInterface;

describe('Argument', function () {

    beforeEach(function () {

        $this->argument = new Argument('argument');

    });

    it('should implement ArgumentInterface', function () {

        expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

    });

    describe('->isBound()', function () {

        it('should return true', function () {

            $test = $this->argument->isBound();

            expect($test)->toBeTruthy();

        });

    });

    describe('->bound()', function () {

        it('should bind the given callable to the argument', function () {

            $callable = mock(CallableInterface::class);

            $test = $this->argument->bound($callable->get());

            expect($test)->toEqual(new CallableWithArgument($callable->get(), ...[
                'argument',
            ]));

        });

    });

});
