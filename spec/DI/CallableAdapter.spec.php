<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\DI\CallableAdapter;
use Quanta\DI\BoundCallableInterface;

describe('CallableAdapter', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->adapter = new CallableAdapter($this->callable);

    });

    it('should implement BoundCallableInterface', function () {

        expect($this->adapter)->toBeAnInstanceOf(BoundCallableInterface::class);

    });

    describe('->expected()', function () {

        it('should return 0', function () {

            $test = $this->adapter->expected();

            expect($test)->toEqual(0);

        });

    });

    describe('->__invoke()', function () {

        it('should invoke the callable with the given arguments', function () {

            $this->callable->with('value1', 'value2', 'value3')->returns('value');

            $test = ($this->adapter)('value1', 'value2', 'value3');

            expect($test)->toEqual('value');

        });

    });

});
