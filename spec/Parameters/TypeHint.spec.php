<?php

use Quanta\DI\Parameters\TypeHint;

describe('TypeHint', function () {

    context('when the type hint is not nullable', function () {

        beforeEach(function () {

            $this->type = new TypeHint(SomeClass::class, false);

        });

        describe('->class()', function () {

            it('should return the class name', function () {

                $test = $this->type->class();

                expect($test)->toEqual(SomeClass::class);

            });

        });

        describe('->isNullable()', function () {

            it('should return false', function () {

                $test = $this->type->isNullable();

                expect($test)->toBeFalsy();

            });

        });

    });

    context('when the type hint is nullable', function () {

        beforeEach(function () {

            $this->type = new TypeHint(SomeClass::class, true);

        });

        describe('->class()', function () {

            it('should return the class name', function () {

                $test = $this->type->class();

                expect($test)->toEqual(SomeClass::class);

            });

        });

        describe('->isNullable()', function () {

            it('should return true', function () {

                $test = $this->type->isNullable();

                expect($test)->toBeTruthy();

            });

        });

    });

});
