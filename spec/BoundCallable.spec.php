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

            $this->callable = new BoundCallable($this->delegate->get(), 'e', 'f');

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
                $parameter4 = mock(ParameterInterface::class);

                $this->delegate->parameters->returns([
                    $parameter1->get(),
                    $parameter2->get(),
                    $parameter3->get(),
                    $parameter4->get(),
                ]);

                $this->delegate->required->returns([
                    $parameter1->get(),
                    $parameter2->get(),
                ]);

                $parameter3->hasDefaultValue->returns(true);
                $parameter3->defaultValue->returns('default1');

                $parameter4->hasDefaultValue->returns(true);
                $parameter4->defaultValue->returns('default2');

            });

            context('when less arguments than the delegate number of required parameters is given', function () {

                it('should throw an ArgumentCountError', function () {

                    $test = function () { ($this->callable)('a'); };

                    expect($test)->toThrow(new ArgumentCountError);

                });

            });

            context('when at least as many arguments as the delegate number of required parameters is given', function () {

                context('when less arguments than the delegate number of parameters is given', function () {

                    it('should complete the given arguments with the default values of the delegate optional parameters and this callable arguments', function () {

                        $this->delegate->__invoke
                            ->with('a', 'b', 'default1', 'default2', 'e', 'f')
                            ->returns('value');

                        $test = ($this->callable)('a', 'b');

                        expect($test)->toEqual('value');

                    });

                });

                context('when as many arguments as the delegate number of parameters is given', function () {

                    it('should append this callable arguments to the given arguments', function () {

                        $this->delegate->__invoke
                            ->with('a', 'b', 'c', 'd', 'e', 'f')
                            ->returns('value');

                        $test = ($this->callable)('a', 'b', 'c', 'd');

                        expect($test)->toEqual('value');

                    });

                });

                context('when more arguments than the delegate number of parameters is given', function () {

                    it('should inject this callable arguments inside the given arguments', function () {

                        $this->delegate->__invoke
                            ->with('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h')
                            ->returns('value');

                        $test = ($this->callable)('a', 'b', 'c', 'd', 'g', 'h');

                        expect($test)->toEqual('value');

                    });

                });

            });

        });

    });

});
