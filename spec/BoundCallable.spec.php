<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\BoundCallable;
use Quanta\DI\BoundCallableInterface;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\Exceptions\ArgumentCountErrorMessage;

describe('BoundCallable', function () {

    beforeEach(function () {

        $this->delegate = mock(BoundCallableInterface::class);
        $this->argument = mock(ArgumentInterface::class);

        $this->callable = new BoundCallable($this->delegate->get(), $this->argument->get());

    });

    it('should implement BoundCallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(BoundCallableInterface::class);

    });

    describe('->expected()', function () {

        beforeEach(function () {

            $this->delegate->expected->returns(1);

        });

        context('when the argument is a placeholder', function () {

            it('should return the delegate number of expected arguments + 1', function () {

                $this->argument->isPlaceholder->returns(true);

                $test = $this->callable->expected();

                expect($test)->toEqual(2);

            });

        });

        context('when the argument is not a placeholder', function () {

            it('should return the delegate number of expected arguments', function () {

                $this->argument->isPlaceholder->returns(false);

                $test = $this->callable->expected();

                expect($test)->toEqual(1);

            });

        });

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->delegate->expected->returns(3);

        });

        context('when the argument is a placeholder', function () {

            beforeEach(function () {

                $this->argument->isPlaceholder->returns(true);

                $this->argument->values->returns([]);

            });

            context('when as many arguments as the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3', 'v4')->returns('value');

                    $test = ($this->callable)('v1', 'v2', 'v3', 'v4');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3', 'v4', 'v5')->returns('value');

                    $test = ($this->callable)('v1', 'v2', 'v3', 'v4', 'v5');

                    expect($test)->toEqual('value');

                });

            });

            context('when less arguments than the number of expected arguments are given', function () {

                it('should throw an ArgumentCountError', function () {

                    ArgumentCountErrorMessage::testing();

                    $test = function () { ($this->callable)('v1', 'v2', 'v3'); };

                    expect($test)->toThrow(new ArgumentCountError(
                        (string) new ArgumentCountErrorMessage(4, 3)
                    ));

                });

            });

        });

        context('when the argument is not a placeholder', function () {

            beforeEach(function () {

                $this->argument->isPlaceholder->returns(false);

                $this->argument->values->returns(['v2', 'v3']);

            });

            context('when as many arguments as the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->callable)('v1');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of expected arguments are given', function () {

                it('should call the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with('v1', 'v2', 'v3', 'v4')->returns('value');

                    $test = ($this->callable)('v1', 'v4');

                    expect($test)->toEqual('value');

                });

            });

            context('when less arguments than the number of expected arguments are given', function () {

                it('should throw an ArgumentCountError', function () {

                    ArgumentCountErrorMessage::testing();

                    $test = function () { ($this->callable)(); };

                    expect($test)->toThrow(new ArgumentCountError(
                        (string) new ArgumentCountErrorMessage(3, 2)
                    ));

                });

            });

        });

    });

});
