<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Injectable;
use Quanta\DI\BoundCallable;
use Quanta\DI\CallableAdapter;
use Quanta\DI\UnboundCallable;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;

describe('Injectable', function () {

    beforeEach(function () {

        $this->callable = stub();

    });

    describe('->injected()', function () {

        beforeEach(function () {

            $this->pool = mock(ArgumentPoolInterface::class);

        });

        context('when there is no parameter', function () {

            it('should return a CallableAdapter from the callable', function () {

                $injectable = new Injectable($this->callable);

                $test = $injectable->injected($this->pool->get());

                expect($test)->toEqual(new CallableAdapter($this->callable));

            });

        });

        context('when there is at least one parameter', function () {

            it('should return a CallableInterface binding the callable to the arguments provided by the argument pool', function () {

                $parameter1 = mock(ParameterInterface::class);
                $parameter2 = mock(ParameterInterface::class);
                $parameter3 = mock(ParameterInterface::class);

                $injectable = new Injectable($this->callable, ...[
                    $parameter1->get(),
                    $parameter2->get(),
                    $parameter3->get(),
                ]);

                $this->pool->arguments->with($parameter1)->returns(['a']);
                $this->pool->arguments->with($parameter2)->returns([]);
                $this->pool->arguments->with($parameter3)->returns(['c', 'd', 'e']);

                $test = $injectable->injected($this->pool->get());

                $expected = new BoundCallable(
                    new UnboundCallable(
                        new BoundCallable(
                            new CallableAdapter($this->callable), 'a'
                        ), $parameter2->get()
                    ), 'c', 'd', 'e'
                );

                expect($test)->toEqual($expected);

            });

        });

    });

});
