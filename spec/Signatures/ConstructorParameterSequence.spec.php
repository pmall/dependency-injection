<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\DI\Signatures\Signature;
use Quanta\DI\Signatures\CallableAdapter;
use Quanta\DI\Signatures\ParameterSequenceInterface;
use Quanta\DI\Signatures\ConstructorParameterSequence;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

require_once __DIR__ . '/../.test/classes.php';

describe('ConstructorParameterSequence', function () {

    context('when the class does not exist', function () {

        beforeEach(function () {

            $this->sequence = new ConstructorParameterSequence(Test\TestNotFound::class);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature adapter from the given callable', function () {

                $callable = mock(CallableInterface::class);

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(new CallableAdapter($callable->get()));

            });

        });

    });

    context('when the class exists', function () {

        context('when the class has no constructor', function () {

            beforeEach(function () {

                $this->sequence = new ConstructorParameterSequence(Test\TestClassWithoutConstructor::class);

            });

            it('should implement ParameterSequenceInterface', function () {

                expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

            });

            describe('->signature()', function () {

                it('should return a signature adapter from the given callable', function () {

                    $callable = mock(CallableInterface::class);

                    $test = $this->sequence->signature($callable->get());

                    expect($test)->toEqual(new CallableAdapter($callable->get()));

                });

            });

        });

        context('when the class has a constructor', function () {

            beforeEach(function () {

                $this->sequence = new ConstructorParameterSequence(Test\TestClassWithConstructor::class);

            });

            it('should implement ParameterSequenceInterface', function () {

                expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

            });

            describe('->signature()', function () {

                it('should return a signature wrapping the class constructor non variadic parameters around the given callable', function () {

                    $callable = mock(CallableInterface::class);

                    $parameters = (new ReflectionClass(Test\TestClassWithConstructor::class))
                        ->getConstructor()
                        ->getParameters();

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

});
