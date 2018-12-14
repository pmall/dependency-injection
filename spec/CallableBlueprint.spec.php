<?php

use function Eloquent\Phony\Kahlan\mock;

use Test\TestClass;

use Quanta\DI\CallableBlueprint;
use Quanta\DI\BlueprintInterface;
use Quanta\DI\Parameters\ParameterInterface;

require_once __DIR__ . '/test/classes.php';

describe('CallableBlueprint', function () {

    it('should implement BlueprintInterface', function () {

        $test = new CallableBlueprint(function () {});

        expect($test)->toBeAnInstanceOf(BlueprintInterface::class);

    });

    describe('->parameters()', function () {

        context('when the callable is an object', function () {

            context('when the callable is a Closure', function () {

                it('should return the callable parameters', function () {

                    $blueprint = new CallableBlueprint(function ($a, $b, $c) {});

                    $test = $blueprint->parameters();

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

                    $blueprint = new CallableBlueprint(new TestClass);

                    $test = $blueprint->parameters();

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

                    $blueprint = new CallableBlueprint([TestClass::class, 'createStatic']);

                    $test = $blueprint->parameters();

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

                    $blueprint = new CallableBlueprint([new TestClass, 'create']);

                    $test = $blueprint->parameters();

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

                $blueprint = new CallableBlueprint('some_callable');

                $test = $blueprint->parameters();

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

    describe('->callable()', function () {

        it('should return the callable', function () {

            $callable = function () {};

            $blueprint = new CallableBlueprint($callable);

            $test = $blueprint->callable();

            expect($test)->toEqual($callable);

        });

    });

});
