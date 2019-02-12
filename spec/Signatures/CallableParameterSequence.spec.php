<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\DI\Signatures\Signature;
use Quanta\DI\Signatures\CallableAdapter;
use Quanta\DI\Signatures\CallableParameterSequence;
use Quanta\DI\Signatures\ParameterSequenceInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

require_once __DIR__ . '/../.test/classes.php';

describe('CallableParameterSequence', function () {

    context('when the callable is a closure', function () {

        beforeEach(function () {

            $this->callable = function ($a, string $b, SomeDependency $c, ?int $d, $e = 'e', ...$f) {
                //
            };

            $this->sequence = new CallableParameterSequence($this->callable);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature wrapping the closure non variadic parameters around the given callable', function () {

                $callable = mock(CallableInterface::class);

                $parameters = (new ReflectionFunction($this->callable))->getParameters();

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(
                    new Signature(
                        new Signature(
                            new Signature(
                                new Signature(
                                    new Signature(
                                        new CallableAdapter($callable->get()),
                                        new ReflectionParameterAdapter($parameters[0])
                                    ),
                                    new ReflectionParameterAdapter($parameters[1])
                                ),
                                new ReflectionParameterAdapter($parameters[2])
                            ),
                            new ReflectionParameterAdapter($parameters[3])
                        ),
                        new ReflectionParameterAdapter($parameters[4])
                    )
                );

            });

        });

    });

    context('when the callable is an invokable object', function () {

        beforeEach(function () {

            $this->callable = new Test\TestClass;

            $this->sequence = new CallableParameterSequence($this->callable);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature wrapping the invokable object non variadic parameters around the given callable', function () {

                $callable = mock(CallableInterface::class);

                $parameters = (new ReflectionMethod($this->callable, '__invoke'))->getParameters();

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(
                    new Signature(
                        new Signature(
                            new Signature(
                                new Signature(
                                    new Signature(
                                        new CallableAdapter($callable->get()),
                                        new ReflectionParameterAdapter($parameters[0])
                                    ),
                                    new ReflectionParameterAdapter($parameters[1])
                                ),
                                new ReflectionParameterAdapter($parameters[2])
                            ),
                            new ReflectionParameterAdapter($parameters[3])
                        ),
                        new ReflectionParameterAdapter($parameters[4])
                    )
                );

            });

        });

    });

    context('when the callable is a static method array', function () {

        beforeEach(function () {

            $this->callable = [Test\TestClass::class, 'createStatic'];

            $this->sequence = new CallableParameterSequence($this->callable);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature wrapping the static method non variadic parameters around the given callable', function () {

                $callable = mock(CallableInterface::class);

                $parameters = (new ReflectionMethod(...$this->callable))->getParameters();

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(
                    new Signature(
                        new Signature(
                            new Signature(
                                new Signature(
                                    new Signature(
                                        new CallableAdapter($callable->get()),
                                        new ReflectionParameterAdapter($parameters[0])
                                    ),
                                    new ReflectionParameterAdapter($parameters[1])
                                ),
                                new ReflectionParameterAdapter($parameters[2])
                            ),
                            new ReflectionParameterAdapter($parameters[3])
                        ),
                        new ReflectionParameterAdapter($parameters[4])
                    )
                );

            });

        });

    });

    context('when the callable is an instance method array', function () {

        beforeEach(function () {

            $this->callable = [new Test\TestClass, 'create'];

            $this->sequence = new CallableParameterSequence($this->callable);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature wrapping the instance method non variadic parameters around the given callable', function () {

                $callable = mock(CallableInterface::class);

                $parameters = (new ReflectionMethod(...$this->callable))->getParameters();

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(
                    new Signature(
                        new Signature(
                            new Signature(
                                new Signature(
                                    new Signature(
                                        new CallableAdapter($callable->get()),
                                        new ReflectionParameterAdapter($parameters[0])
                                    ),
                                    new ReflectionParameterAdapter($parameters[1])
                                ),
                                new ReflectionParameterAdapter($parameters[2])
                            ),
                            new ReflectionParameterAdapter($parameters[3])
                        ),
                        new ReflectionParameterAdapter($parameters[4])
                    )
                );

            });

        });

    });

    context('when the callable is a function name', function () {

        beforeAll(function () {

            function test_callable ($a, string $b, SomeDependency $c, ?int $d, $e = 'e', ...$f) {
                //
            };

        });

        beforeEach(function () {

            $this->callable = 'test_callable';

            $this->sequence = new CallableParameterSequence($this->callable);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature wrapping the function non variadic parameters around the given callable', function () {

                $callable = mock(CallableInterface::class);

                $parameters = (new ReflectionFunction($this->callable))->getParameters();

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(
                    new Signature(
                        new Signature(
                            new Signature(
                                new Signature(
                                    new Signature(
                                        new CallableAdapter($callable->get()),
                                        new ReflectionParameterAdapter($parameters[0])
                                    ),
                                    new ReflectionParameterAdapter($parameters[1])
                                ),
                                new ReflectionParameterAdapter($parameters[2])
                            ),
                            new ReflectionParameterAdapter($parameters[3])
                        ),
                        new ReflectionParameterAdapter($parameters[4])
                    )
                );

            });

        });

    });

});
