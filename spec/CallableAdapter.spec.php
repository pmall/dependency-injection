<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\DI\CallableAdapter;
use Quanta\DI\CallableInterface;

describe('CallableAdapter', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->adapter = new CallableAdapter($this->callable);

    });

    it('should implement CallableInterface', function () {

        expect($this->adapter)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->parameters()', function () {

        it('should return an empty array', function () {

            $test = $this->adapter->parameters();

            expect($test)->toEqual([]);

        });

    });

    describe('->required()', function () {

        it('should return an empty array', function () {

            $test = $this->adapter->required();

            expect($test)->toEqual([]);

        });

    });

    describe('->optional()', function () {

        it('should return an empty array', function () {

            $test = $this->adapter->optional();

            expect($test)->toEqual([]);

        });

    });

    describe('->__invoke()', function () {

        it('should invoke the callable with the given arguments', function () {

            $this->callable->with('a', 'b', 'c')->returns('value');

            $test = ($this->adapter)('a', 'b', 'c');

            expect($test)->toEqual('value');

        });

    });

});
