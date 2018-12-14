<?php

use function Eloquent\Phony\Kahlan\mock;

use Test\TestClass;

use Quanta\DI\SomeCallable;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ParameterCollectionInterface;

require_once __DIR__ . '/test/classes.php';

describe('SomeCallable', function () {

    it('should implement ParameterCollectionInterface', function () {

        $test = new SomeCallable(function () {});

        expect($test)->toBeAnInstanceOf(ParameterCollectionInterface::class);

    });

    describe('->parameters()', function () {

        context('when the callable is an object', function () {

            context('when the callable is a Closure', function () {

                it('should return the callable parameters', function () {

                    $callable = new SomeCallable(function ($a, $b, $c) {});

                    $test = $callable->parameters();

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(3);
                    expect($test[0])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[1])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[2])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[0]->name())->toEqual('a');
                    expect($test[1]->name())->toEqual('b');
                    expect($test[2]->name())->toEqual('c');

                });

            });

            context('when the callable is an invokable object', function () {

                it('should return the callable parameters', function () {

                    $callable = new SomeCallable(new TestClass);

                    $test = $callable->parameters();

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(3);
                    expect($test[0])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[1])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[2])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[0]->name())->toEqual('a');
                    expect($test[1]->name())->toEqual('b');
                    expect($test[2]->name())->toEqual('c');

                });

            });

        });

        context('when the callable is an array', function () {

            context('when the callable is a static method', function () {

                it('should return the callable parameters', function () {

                    $callable = new SomeCallable([TestClass::class, 'createStatic']);

                    $test = $callable->parameters();

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(3);
                    expect($test[0])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[1])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[2])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[0]->name())->toEqual('a');
                    expect($test[1]->name())->toEqual('b');
                    expect($test[2]->name())->toEqual('c');

                });

            });

            context('when the callable is an instance method', function () {

                it('should return the callable parameters', function () {

                    $callable = new SomeCallable([new TestClass, 'create']);

                    $test = $callable->parameters();

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(3);
                    expect($test[0])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[1])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[2])->toBeAnInstanceOf(ParameterInterface::class);
                    expect($test[0]->name())->toEqual('a');
                    expect($test[1]->name())->toEqual('b');
                    expect($test[2]->name())->toEqual('c');

                });

            });

        });

        context('when the callable is a string', function () {

            it('should return the callable parameters', function () {

                function some_callable ($a, $b, $c) {}

                $callable = new SomeCallable('some_callable');

                $test = $callable->parameters();

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(3);
                expect($test[0])->toBeAnInstanceOf(ParameterInterface::class);
                expect($test[1])->toBeAnInstanceOf(ParameterInterface::class);
                expect($test[2])->toBeAnInstanceOf(ParameterInterface::class);
                expect($test[0]->name())->toEqual('a');
                expect($test[1]->name())->toEqual('b');
                expect($test[2]->name())->toEqual('c');

            });

        });

    });

});
