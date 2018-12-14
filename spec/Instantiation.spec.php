<?php

use Test\TestClass;

use Quanta\DI\Instantiation;

require_once __DIR__ . '/test/classes.php';

describe('Instantiation', function () {

    describe('->__invoke()', function () {

        it('should return an instance of the class with the given arguments', function () {

            $callable = new Instantiation(TestClass::class);

            $test = $callable('a', 'b', 'c');

            expect($test)->toEqual(new TestClass('a', 'b', 'c'));

        });

    });

});
