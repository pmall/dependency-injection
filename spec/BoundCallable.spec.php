<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\BoundCallable;
use Quanta\DI\BoundCallableInterface;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('BoundCallable', function () {

    beforeEach(function () {

        $this->delegate = mock(BoundCallableInterface::class);
        $this->argument = mock(ArgumentInterface::class);

        $this->callable = new BoundCallable($this->delegate->get(), $this->argument->get());

    });

    it('should implement BoundCallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(BoundCallableInterface::class);

    });

    describe('->unbound()', function () {

        beforeEach(function () {

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);

            $this->delegate->unbound->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
            ]);

        });

        context('when the argument is a placeholder', function () {

            it('should add true to the given vector and call the delegate ->unbound() method with the new vector', function () {

                $this->argument->isPlaceholder->returns(true);

                $test = $this->callable->unbound(true, false);

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(2);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());

                $this->delegate->unbound->once()->calledWith(true, false, true);

            });

        });

        context('when the argument is not a placeholder', function () {

            it('should add false to the given vector and call the delegate ->unbound() method with the new vector', function () {

                $this->argument->isPlaceholder->returns(false);

                $test = $this->callable->unbound(true, false);

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(2);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());

                $this->delegate->unbound->once()->calledWith(true, false, false);

            });

        });

    });

    describe('->__invoke()', function () {

        context('when the argument is a placeholder', function () {

            beforeEach(function () {

                $this->delegate->unbound->with(true)->returns([
                    mock(ParameterInterface::class)->get(),
                    mock(ParameterInterface::class)->get(),
                    mock(ParameterInterface::class)->get(),
                ]);

                $this->argument->isPlaceholder->returns(true);

                $this->argument->values->returns([]);

            });

            context('when as many arguments as the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->callable)('v1', 'v2', 'v3');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3', 'v4')->returns('value');

                    $test = ($this->callable)('v1', 'v2', 'v3', 'v4');

                    expect($test)->toEqual('value');

                });

            });

            context('when less arguments than the number of expected arguments are given', function () {

                it('should throw an ArgumentCountError', function () {

                    $test = function () { ($this->callable)('v1', 'v2'); };

                    expect($test)->toThrow(new ArgumentCountError);

                });

            });

        });

        context('when the argument is not a placeholder', function () {

            beforeEach(function () {

                $this->delegate->unbound->with(false)->returns([
                    mock(ParameterInterface::class)->get(),
                    mock(ParameterInterface::class)->get(),
                ]);

                $this->argument->isPlaceholder->returns(false);

                $this->argument->values->returns(['v3', 'v4']);

            });

            context('when as many arguments as the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3', 'v4')->returns('value');

                    $test = ($this->callable)('v1', 'v2');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3', 'v4', 'v5')->returns('value');

                    $test = ($this->callable)('v1', 'v2', 'v5');

                    expect($test)->toEqual('value');

                });

            });

            context('when less arguments than the number of expected arguments are given', function () {

                it('should throw an ArgumentCountError', function () {

                    $test = function () { ($this->callable)('v1'); };

                    expect($test)->toThrow(new ArgumentCountError);

                });

            });

        });

    });

});
