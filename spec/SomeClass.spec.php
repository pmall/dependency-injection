<?php

use function Eloquent\Phony\Kahlan\mock;

use Test\TestClass;
use Test\TestClassWithoutParameter;
use Test\TestClassWithoutConstructor;

use Quanta\DI\SomeClass;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ParameterCollectionInterface;

require_once __DIR__ . '/test/classes.php';

describe('SomeClass', function () {

    it('should implement ParameterCollectionInterface', function () {

        $test = new SomeClass(TestClass::class);

        expect($test)->toBeAnInstanceOf(ParameterCollectionInterface::class);

    });

    describe('->parameters()', function () {

        context('when the class has no constructor', function () {

            it('should return an empty array', function () {

                $class = new SomeClass(TestClassWithoutConstructor::class);

                $test = $class->parameters();

                expect($test)->toEqual([]);

            });

        });

        context('when the class has a constructor', function () {

            context('when the class constructor has no parameter', function () {

                it('should return an empty array', function () {

                    $class = new SomeClass(TestClassWithoutParameter::class);

                    $test = $class->parameters();

                    expect($test)->toEqual([]);

                });

            });

            context('when the class constructor has parameters', function () {

                it('should return an array of the class constructor parameters', function () {

                    $class = new SomeClass(TestClass::class);

                    $test = $class->parameters();

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

});
