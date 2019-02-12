<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\PA\PlaceholderSequence;

use Quanta\DI\Autowirable;
use Quanta\DI\AutowirableInterface;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Signatures\SignatureInterface;
use Quanta\DI\Signatures\CallableParameterSequence;
use Quanta\DI\Signatures\ParameterSequenceInterface;
use Quanta\DI\Signatures\ConstructorParameterSequence;

describe('Autowirable::fromCallable()', function () {

    it('should return a new autowirable from the given callable', function () {

        $callable = function () {};

        $test = Autowirable::fromCallable($callable);

        expect($test)->toEqual(new Autowirable(
            new CallableAdapter($callable),
            new CallableParameterSequence($callable)
        ));

    });

});

describe('Autowirable::fromClass()', function () {

    it('should return a new autowirable from the given class name', function () {

        $test = Autowirable::fromClass(SomeClass::class);

        expect($test)->toEqual(new Autowirable(
            new ConstructorAdapter(SomeClass::class),
            new ConstructorParameterSequence(SomeClass::class)
        ));

    });

});

describe('Autowirable', function () {

    beforeEach(function () {

        $this->callable = mock(CallableInterface::class);
        $this->sequence = mock(ParameterSequenceInterface::class);

        $this->autowirable = new Autowirable(
            $this->callable->get(),
            $this->sequence->get()
        );

    });

    it('should implement AutowirableInterface', function () {

        expect($this->autowirable)->toBeAnInstanceOf(AutowirableInterface::class);

    });

    describe('->autowirable()', function () {

        beforeEach(function () {

            $signature = mock(SignatureInterface::class);

            $this->pool = mock(ArgumentPoolInterface::class);
            $this->bound = mock(CallableInterface::class);

            $this->sequence->signature->with($this->callable)->returns($signature);

            $signature->bound->with($this->pool)->returns($this->bound);

        });

        context('when the bound callable has no placeholder', function () {

            beforeEach(function () {

                $this->bound->placeholders->returns(new PlaceholderSequence);

            });

            context('when no argument are given', function () {

                it('should proxy the bound callable with no argument', function () {

                    $this->bound->__invoke->with()->returns('value');

                    $test = ($this->autowirable)($this->pool->get());

                    expect($test)->toEqual('value');

                });

            });

            context('when arguments are given', function () {

                it('should proxy the bound callable with the arguments', function () {

                    $this->bound->__invoke->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->autowirable)($this->pool->get(), 'v1', 'v2', 'v3');

                    expect($test)->toEqual('value');

                });

            });

        });

        context('when the bound callable has placeholders', function () {

            beforeEach(function () {

                $this->bound->placeholders->returns(new PlaceholderSequence('p1', 'p2', 'p3'));

            });

            context('when less arguments than the number of placeholders is given', function () {

                it('should throw a LogicException', function () {

                    $test = function () {
                        ($this->autowirable)($this->pool->get(), 'v1', 'v2');
                    };

                    expect($test)->toThrow(new LogicException);

                });

                it('should display the callable string representation and the unbound parameter names', function () {

                    $this->bound->str->returns('str');

                    try {
                        ($this->autowirable)($this->pool->get(), 'v1');
                    }

                    catch (LogicException $e) {
                        $test = $e->getMessage();
                    }

                    expect($test)->toContain('function str()');
                    expect($test)->toContain('[$p2, $p3]');

                });

            });

            context('when as many arguments as the number of placeholders is given', function () {

                it('should proxy the bound callable with the arguments', function () {

                    $this->bound->__invoke->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->autowirable)($this->pool->get(), 'v1', 'v2', 'v3');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of placeholders is given', function () {

                it('should proxy the bound callable with the arguments', function () {

                    $this->bound->__invoke->with('v1', 'v2', 'v3', 'v4', 'v5')->returns('value');

                    $test = ($this->autowirable)($this->pool->get(), 'v1', 'v2', 'v3', 'v4', 'v5');

                    expect($test)->toEqual('value');

                });

            });

        });

    });

});
