<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\DI\Arguments\UnboundArgument;
use Quanta\DI\Arguments\ArgumentInterface;

describe('UnboundArgument', function () {

    beforeEach(function () {

        $this->argument = new UnboundArgument;

    });

    it('should implement ArgumentInterface', function () {

        expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

    });

    describe('->isBound()', function () {

        it('should return false', function () {

            $test = $this->argument->isBound();

            expect($test)->toBeFalsy();

        });

    });

    describe('->bound()', function () {

        it('should throw a LogicException', function () {

            $callable = mock(CallableInterface::class);

            $test = function () use ($callable) {
                $this->argument->bound($callable->get());
            };

            expect($test)->toThrow(new LogicException);

        });

    });

});
