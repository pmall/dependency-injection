<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\BoundCallableInterface;
use Quanta\DI\InjectableCallableAdapter;
use Quanta\DI\InjectableCallableInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('InjectableCallableAdapter', function () {

    beforeEach(function () {

        $this->callable = mock(InjectableCallableInterface::class);

        $this->adapter = new InjectableCallableAdapter($this->callable->get());

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

    describe('->unbound()', function () {

        it('should return an array containing the given parameters', function () {

            $parameter1 = mock(ParameterInterface::class);
            $parameter2 = mock(ParameterInterface::class);

            $test = $this->adapter->unbound(...[
                $parameter1->get(),
                $parameter2->get(),
            ]);

            expect($test)->toBeAn('array');
            expect($test)->toHaveLength(2);
            expect($test[0])->toBe($parameter1->get());
            expect($test[1])->toBe($parameter2->get());

        });

    });

    describe('->__invoke()', function () {

        it('should invoke the callable with the given arguments', function () {

            $this->callable->__invoke->with('a', 'b', 'c')->returns('value');

            $test = ($this->adapter)('a', 'b', 'c');

            expect($test)->toEqual('value');

        });

    });

});
