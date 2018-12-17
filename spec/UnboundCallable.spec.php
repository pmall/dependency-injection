<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\UnboundCallable;
use Quanta\DI\CallableInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('UnboundCallable', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);
        $this->parameter = mock(ParameterInterface::class);

        $this->callable = new UnboundCallable($this->delegate->get(), $this->parameter->get());

    });

    it('should implement CallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->parameters()', function () {

        it('should merge the delegate parameters with this one', function () {

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
            expect($test)->toHaveLength(4);
            expect($test[0])->toBe($parameter1->get());
            expect($test[1])->toBe($parameter2->get());
            expect($test[2])->toBe($parameter3->get());
            expect($test[3])->toBe($this->parameter->get());

        });

    });

    describe('->required()', function () {

        beforeEach(function () {

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);
            $this->parameter3 = mock(ParameterInterface::class);
            $this->parameter4 = mock(ParameterInterface::class);

            $this->delegate->parameters->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
                $this->parameter4->get(),
            ]);

            $this->delegate->required->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
            ]);

        });

        context('when the parameter has a default value', function () {

            it('should return the delegate required parameters', function () {

                $this->parameter->hasDefaultValue->returns(true);
                $this->parameter->allowsNull->returns(false);
                $this->parameter->isVariadic->returns(false);

                $test = $this->callable->required();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());

            });

        });

        context('when the parameter is nullable', function () {

            it('should return the delegate required parameters', function () {

                $this->parameter->hasDefaultValue->returns(false);
                $this->parameter->allowsNull->returns(true);
                $this->parameter->isVariadic->returns(false);

                $test = $this->callable->required();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());

            });

        });

        context('when the parameter is variadic', function () {

            it('should return the delegate required parameters', function () {

                $this->parameter->hasDefaultValue->returns(false);
                $this->parameter->allowsNull->returns(false);
                $this->parameter->isVariadic->returns(true);

                $test = $this->callable->required();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());

            });
        });

        context('when no value can be inferred from the parameter', function () {

            it('should merge the delegate parameters with this one', function () {

                $this->parameter->hasDefaultValue->returns(false);
                $this->parameter->allowsNull->returns(false);
                $this->parameter->isVariadic->returns(false);

                $test = $this->callable->required();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(5);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());
                expect($test[3])->toBe($this->parameter4->get());
                expect($test[4])->toBe($this->parameter->get());

            });

        });

    });

    describe('->optional()', function () {

        beforeEach(function () {

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);
            $this->parameter3 = mock(ParameterInterface::class);

            $this->delegate->optional->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
            ]);

        });

        context('when the parameter has a default value', function () {

            it('should merge the delegate optional parameters with this one', function () {

                $this->parameter->hasDefaultValue->returns(true);
                $this->parameter->allowsNull->returns(false);
                $this->parameter->isVariadic->returns(false);

                $test = $this->callable->optional();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(4);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());
                expect($test[3])->toBe($this->parameter->get());

            });

        });

        context('when the parameter is nullable', function () {

            it('should merge the delegate optional parameters with this one', function () {

                $this->parameter->hasDefaultValue->returns(false);
                $this->parameter->allowsNull->returns(true);
                $this->parameter->isVariadic->returns(false);

                $test = $this->callable->optional();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(4);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());
                expect($test[3])->toBe($this->parameter->get());

            });

        });

        context('when the parameter is variadic', function () {

            it('should merge the delegate optional parameters with this one', function () {

                $this->parameter->hasDefaultValue->returns(false);
                $this->parameter->allowsNull->returns(false);
                $this->parameter->isVariadic->returns(true);

                $test = $this->callable->optional();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(4);
                expect($test[0])->toBe($this->parameter1->get());
                expect($test[1])->toBe($this->parameter2->get());
                expect($test[2])->toBe($this->parameter3->get());
                expect($test[3])->toBe($this->parameter->get());

            });

        });

        context('when no value can be inferred from the parameter', function () {

            it('should return an empty array', function () {

                $this->parameter->hasDefaultValue->returns(false);
                $this->parameter->allowsNull->returns(false);
                $this->parameter->isVariadic->returns(false);

                $test = $this->callable->optional();

                expect($test)->toEqual([]);

            });

        });

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);
            $this->parameter3 = mock(ParameterInterface::class);
            $this->parameter4 = mock(ParameterInterface::class);
            $this->parameter5 = mock(ParameterInterface::class);

            $this->delegate->parameters->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
                $this->parameter4->get(),
                $this->parameter5->get(),
            ]);

            $this->delegate->required->returns([
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
            ]);

            $this->delegate->optional->returns([
                $this->parameter4->get(),
                $this->parameter5->get(),
            ]);

            $this->parameter4->hasDefaultValue->returns(true);
            $this->parameter4->allowsNull->returns(false);

            $this->parameter5->hasDefaultValue->returns(false);
            $this->parameter5->allowsNull->returns(true);

            $this->parameter4->defaultValue->returns('d');

        });

        context('when less arguments than the delegate number of required parameters is given', function () {

            it('should throw an ArgumentCountError', function () {

                $test = function () { ($this->callable)('a', 'b'); };

                expect($test)->toThrow(new ArgumentCountError);

            });

        });

        context('when as many arguments as the delegate number of required parameters is given', function () {

            context('when the parameter has a default value', function () {

                it('should invoke the delegate with the given arguments, the default values of the optional parameters and the default value of this parameter', function () {

                    $this->parameter->hasDefaultValue->returns(true);
                    $this->parameter->allowsNull->returns(false);
                    $this->parameter->isVariadic->returns(false);

                    $this->parameter->defaultValue->returns('e');

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', null, 'e')->returns('value');

                    $test = ($this->callable)('a', 'b', 'c');

                    expect($test)->toEqual('value');

                });

            });

            context('when the parameter is nullable', function () {

                it('should invoke the delegate with the given arguments, the default values of the optional parameters and null', function () {

                    $this->parameter->hasDefaultValue->returns(false);
                    $this->parameter->allowsNull->returns(true);
                    $this->parameter->isVariadic->returns(false);

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', null, null)->returns('value');

                    $test = ($this->callable)('a', 'b', 'c');

                    expect($test)->toEqual('value');

                });

            });

            context('when the parameter is variadic', function () {

                it('should invoke the delegate with the given arguments and the default values of the optional parameters', function () {

                    $this->parameter->hasDefaultValue->returns(false);
                    $this->parameter->allowsNull->returns(false);
                    $this->parameter->isVariadic->returns(true);

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', null)->returns('value');

                    $test = ($this->callable)('a', 'b', 'c');

                    expect($test)->toEqual('value');

                });

            });

        });

        context('when more arguments than the delegate number of required parameters is given', function () {

            context('when less arguments than the number of parameters is given', function () {

                context('when the parameter has a default value', function () {

                    it('should invoke the delegate with the given arguments, the default values of the optional parameters with no argument and the default value of this parameter', function () {

                        $this->parameter->hasDefaultValue->returns(true);
                        $this->parameter->allowsNull->returns(false);
                        $this->parameter->isVariadic->returns(false);

                        $this->parameter->defaultValue->returns('e');

                        $this->delegate->__invoke->with('a', 'b', 'c', 'd', null, 'e')->returns('value');

                        $test = ($this->callable)('a', 'b', 'c', 'd');

                        expect($test)->toEqual('value');

                    });

                });

                context('when the parameter is nullable', function () {

                    it('should invoke the delegate with the given arguments, the default values of the optional parameters with no argument and null', function () {

                        $this->parameter->hasDefaultValue->returns(false);
                        $this->parameter->allowsNull->returns(true);
                        $this->parameter->isVariadic->returns(false);

                        $this->delegate->__invoke->with('a', 'b', 'c', 'd', null, null)->returns('value');

                        $test = ($this->callable)('a', 'b', 'c', 'd');

                        expect($test)->toEqual('value');

                    });

                });

                context('when the parameter is variadic', function () {

                    it('should invoke the delegate with the given arguments and the default values of the optional parameters with no argument', function () {

                        $this->parameter->hasDefaultValue->returns(false);
                        $this->parameter->allowsNull->returns(false);
                        $this->parameter->isVariadic->returns(true);

                        $this->delegate->__invoke->with('a', 'b', 'c', 'd', null)->returns('value');

                        $test = ($this->callable)('a', 'b', 'c', 'd');

                        expect($test)->toEqual('value');

                    });

                });

            });

            context('when as many arguments as the number of parameters is given', function () {

                it('should invoke the delegate with the given arguments', function () {

                    $this->parameter->hasDefaultValue->returns(true);
                    $this->parameter->allowsNull->returns(false);
                    $this->parameter->isVariadic->returns(false);

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', 'e', 'f')->returns('value');

                    $test = ($this->callable)('a', 'b', 'c', 'd', 'e', 'f');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of parameters is given', function () {

                it('should invoke the delegate with the given arguments', function () {

                    $this->parameter->hasDefaultValue->returns(true);
                    $this->parameter->allowsNull->returns(false);
                    $this->parameter->isVariadic->returns(false);

                    $this->delegate->__invoke->with('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h')->returns('value');

                    $test = ($this->callable)('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');

                    expect($test)->toEqual('value');

                });

            });

        });

    });

});