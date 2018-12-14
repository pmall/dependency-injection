<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\CallableAdapter;
use Quanta\DI\BoundCallableInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('CallableAdapter', function () {

    beforeEach(function () {

        $this->callable = stub();
        $this->parameter1 = mock(ParameterInterface::class);
        $this->parameter2 = mock(ParameterInterface::class);
        $this->parameter3 = mock(ParameterInterface::class);

        $this->adapter = new CallableAdapter($this->callable, ...[
            $this->parameter1->get(),
            $this->parameter2->get(),
            $this->parameter3->get(),
        ]);

    });

    it('should implement BoundCallableInterface', function () {

        expect($this->adapter)->toBeAnInstanceOf(BoundCallableInterface::class);

    });

    describe('->unbound()', function () {

        it('should return the parameters at the position of the true values of the given vector', function () {

            $test = $this->adapter->unbound(true, false, true);

            expect($test)->toBeAn('array');
            expect($test)->toHaveLength(2);
            expect($test[0])->toBe($this->parameter1->get());
            expect($test[1])->toBe($this->parameter3->get());

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
