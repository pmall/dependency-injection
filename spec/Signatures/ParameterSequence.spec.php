<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\DI\Signatures\Signature;
use Quanta\DI\Signatures\CallableAdapter;
use Quanta\DI\Signatures\ParameterSequence;
use Quanta\DI\Signatures\ParameterSequenceInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('ParameterSequence', function () {

    context('when there is no parameter', function () {

        beforeEach(function () {

            $this->sequence = new ParameterSequence;

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

    context('when there is at least one parameter', function () {

        beforeEach(function () {

            $this->parameter1 = mock(ParameterInterface::class);
            $this->parameter2 = mock(ParameterInterface::class);
            $this->parameter3 = mock(ParameterInterface::class);

            $this->sequence = new ParameterSequence(...[
                $this->parameter1->get(),
                $this->parameter2->get(),
                $this->parameter3->get(),
            ]);

        });

        it('should implement ParameterSequenceInterface', function () {

            expect($this->sequence)->toBeAnInstanceOf(ParameterSequenceInterface::class);

        });

        describe('->signature()', function () {

            it('should return a signature wrapping the parameters around the given callable', function () {

                $callable = mock(CallableInterface::class);

                $test = $this->sequence->signature($callable->get());

                expect($test)->toEqual(
                    new Signature(
                        new Signature(
                            new Signature(
                                new CallableAdapter($callable->get()),
                                $this->parameter1->get()
                            ),
                            $this->parameter2->get()
                        ),
                        $this->parameter3->get()
                    )
                );

            });

        });

    });

});
