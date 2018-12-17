<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\BoundCallable;
use Quanta\DI\CallableInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('BoundCallable', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

    });

    context('when there is no argument', function () {

        it('should throw an ArgumentCountError', function () {

            $test = function () { new BoundCallable($this->delegate->get()); };

            expect($test)->toThrow(new ArgumentCountError);

        });

    });

    context('when there is at least one argument', function () {

        beforeEach(function () {

            $this->callable = new BoundCallable($this->delegate->get(), 'd', 'e');

        });

        it('should implement CallableInterface', function () {

            expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

        });

        describe('->parameters()', function () {

            it('should return the delegate parameters', function () {

                $parameter1 = mock(ParameterInterface::class);
                $parameter2 = mock(ParameterInterface::class);
                $parameter3 = mock(ParameterInterface::class);

                $this->delegate->parameters->returns([
                    $parameter1->get(),
                    $parameter2->get(),
                    $parameter3->get(),
                ]);

                $test = $this->callable->parameters();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBe($parameter1->get());
                expect($test[1])->toBe($parameter2->get());
                expect($test[2])->toBe($parameter3->get());

            });

        });

        describe('->required()', function () {

            it('should return the delegate required parameters', function () {

                $parameter1 = mock(ParameterInterface::class);
                $parameter2 = mock(ParameterInterface::class);
                $parameter3 = mock(ParameterInterface::class);

                $this->delegate->required->returns([
                    $parameter1->get(),
                    $parameter2->get(),
                    $parameter3->get(),
                ]);

                $test = $this->callable->required();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBe($parameter1->get());
                expect($test[1])->toBe($parameter2->get());
                expect($test[2])->toBe($parameter3->get());

            });

        });

        describe('->optional()', function () {

            it('should return the delegate optional parameter', function () {

                $parameter1 = mock(ParameterInterface::class);
                $parameter2 = mock(ParameterInterface::class);
                $parameter3 = mock(ParameterInterface::class);

                $this->delegate->optional->returns([
                    $parameter1->get(),
                    $parameter2->get(),
                    $parameter3->get(),
                ]);

                $test = $this->callable->optional();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBe($parameter1->get());
                expect($test[1])->toBe($parameter2->get());
                expect($test[2])->toBe($parameter3->get());

            });

        });

        describe('->__invoke()', function () {

            beforeEach(function () {

                $parameter1 = mock(ParameterInterface::class);
                $parameter2 = mock(ParameterInterface::class);
                $parameter3 = mock(ParameterInterface::class);

                $this->delegate->required->returns([
                    $parameter1->get(),
                    $parameter2->get(),
                    $parameter3->get(),
                ]);

            });

            context('when less arguments than the delegate number of required parameters is given', function () {

                it('should throw an ArgumentCountError', function () {

                    $test = function () { ($this->callable)('a', 'b'); };

                    expect($test)->toThrow(new ArgumentCountError);

                });

            });

            context('when as many arguments as the delegate number of required parameters is given', function () {

                it('should invoke the delegate with the given arguments and the bound arguments', function () {

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', 'e')->returns('value');

                    $test = ($this->callable)('a', 'b', 'c');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the delegate number of required parameters is given', function () {

                it('should invoke the delegate with the given arguments, the bound arguments and the extra arguments', function () {

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', 'e', 'f', 'g')->returns('value');

                    $test = ($this->callable)('a', 'b', 'c', 'f', 'g');

                    expect($test)->toEqual('value');

                });

            });

        });

    });

});
