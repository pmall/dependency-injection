<?php

use function Eloquent\Phony\Kahlan\mock;

use Test\TestClass;
use Test\TestClassWithoutParameter;
use Test\TestClassWithoutConstructor;

use Quanta\DI\Instantiation;
use Quanta\DI\InstanceBlueprint;
use Quanta\DI\BlueprintInterface;
use Quanta\DI\Parameters\ParameterInterface;

require_once __DIR__ . '/test/classes.php';

describe('InstanceBlueprint', function () {

    it('should implement BlueprintInterface', function () {

        $test = new InstanceBlueprint(TestClass::class);

        expect($test)->toBeAnInstanceOf(BlueprintInterface::class);

    });

    describe('->name()', function () {

        it('should return the name of the class', function () {

            $blueprint = new InstanceBlueprint(TestClass::class);

            $test = $blueprint->name();

            expect($test)->toEqual(TestClass::class);

        });

    });

    describe('->parameters()', function () {

        context('when the class has no constructor', function () {

            it('should return an empty array', function () {

                $blueprint = new InstanceBlueprint(TestClassWithoutConstructor::class);

                $test = $blueprint->parameters();

                expect($test)->toEqual([]);

            });

        });

        context('when the class has a constructor', function () {

            context('when the class constructor has no parameter', function () {

                it('should return an empty array', function () {

                    $blueprint = new InstanceBlueprint(TestClassWithoutParameter::class);

                    $test = $blueprint->parameters();

                    expect($test)->toEqual([]);

                });

            });

            context('when the class constructor has parameters', function () {

                it('should return an array of the class constructor parameters', function () {

                    $blueprint = new InstanceBlueprint(TestClass::class);

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

    });

    describe('->__invoke()', function () {

        it('should instantiate the class with the given arguments', function () {

            $blueprint = new InstanceBlueprint(TestClass::class);

            $test = $blueprint->callable();

            expect($test)->toEqual(new Instantiation(TestClass::class));

        });

    });

});
