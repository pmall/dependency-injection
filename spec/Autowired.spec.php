<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\PA\PlaceholderSequence;
use Quanta\DI\Autowired;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\CompositeArgumentPool;
use Quanta\DI\Signatures\Signature;
use Quanta\DI\Signatures\SignatureInterface;
use Quanta\DI\Signatures\CallableParameterSequence;
use Quanta\DI\Signatures\ConstructorParameterSequence;

describe('Autowired::fromCallable()', function () {

    it('should return a new autowired from the given callable and argument pools', function () {

        $callable = function () {};

        $pool1 = mock(ArgumentPoolInterface::class);
        $pool2 = mock(ArgumentPoolInterface::class);
        $pool3 = mock(ArgumentPoolInterface::class);

        $test = Autowired::fromCallable($callable, ...[
            $pool1->get(),
            $pool2->get(),
            $pool3->get(),
        ]);

        expect($test)->toEqual(new Autowired(
            new Signature(
                new CallableAdapter($callable),
                new CallableParameterSequence($callable)
            ),
            $pool1->get(),
            $pool2->get(),
            $pool3->get()
        ));

    });

});

describe('Autowired::fromClass()', function () {

    it('should return a new autowired from the given class name and argument pools', function () {

        $pool1 = mock(ArgumentPoolInterface::class);
        $pool2 = mock(ArgumentPoolInterface::class);
        $pool3 = mock(ArgumentPoolInterface::class);

        $test = Autowired::fromClass(SomeClass::class, ...[
            $pool1->get(),
            $pool2->get(),
            $pool3->get(),
        ]);

        expect($test)->toEqual(new Autowired(
            new Signature(
                new ConstructorAdapter(SomeClass::class),
                new ConstructorParameterSequence(SomeClass::class)
            ),
            $pool1->get(),
            $pool2->get(),
            $pool3->get()
        ));

    });


});

describe('Autowired', function () {

    beforeEach(function () {

        $this->signature = mock(SignatureInterface::class);

        $this->pool1 = mock(ArgumentPoolInterface::class);
        $this->pool2 = mock(ArgumentPoolInterface::class);
        $this->pool3 = mock(ArgumentPoolInterface::class);

        $this->autowired = new Autowired($this->signature->get(), ...[
            $this->pool1->get(),
            $this->pool2->get(),
            $this->pool3->get(),
        ]);

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $pool = new CompositeArgumentPool(...[
                $this->pool1->get(),
                $this->pool2->get(),
                $this->pool3->get(),
            ]);

            $this->bound = mock(CallableInterface::class);

            $this->signature->bound->with($pool)->returns($this->bound);

        });

        context('when the autowired callable has no placeholder', function () {

            beforeEach(function () {

                $this->bound->placeholders->returns(new PlaceholderSequence);

            });

            context('when no argument are given', function () {

                it('should proxy the autowired callable with no argument', function () {

                    $this->bound->__invoke->with()->returns('value');

                    $test = ($this->autowired)();

                    expect($test)->toEqual('value');

                });

            });

            context('when arguments are given', function () {

                it('should proxy the bound callable with the arguments', function () {

                    $this->bound->__invoke->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->autowired)('v1', 'v2', 'v3');

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
                        ($this->autowired)('v1', 'v2');
                    };

                    expect($test)->toThrow(new LogicException);

                });

                it('should display the callable string representation and the unbound parameter names', function () {

                    $this->bound->str->returns('str');

                    try {
                        ($this->autowired)('v1');
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

                    $test = ($this->autowired)('v1', 'v2', 'v3');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the number of placeholders is given', function () {

                it('should proxy the bound callable with the arguments', function () {

                    $this->bound->__invoke->with('v1', 'v2', 'v3', 'v4', 'v5')->returns('value');

                    $test = ($this->autowired)('v1', 'v2', 'v3', 'v4', 'v5');

                    expect($test)->toEqual('value');

                });

            });

        });

    });

});
