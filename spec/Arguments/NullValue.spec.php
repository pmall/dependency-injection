<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Arguments\NullValue;
use Quanta\DI\Arguments\ArgumentPoolInterface;

use Quanta\DI\Parameters\ParameterInterface;

describe('NullValue', function () {

    beforeEach(function () {

        $this->pool = new NullValue;

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->arguments()', function () {

        beforeEach(function () {

            $this->parameter = mock(ParameterInterface::class);

        });

        context('when the parameter allows null', function () {

            beforeEach(function () {

                $this->parameter->allowsNull->returns(true);

            });

            context('when the parameter has no default value and is not variadic', function () {

                it('should return an array containing null', function () {

                    $this->parameter->hasDefaultValue->returns(false);
                    $this->parameter->isVariadic->returns(false);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(1);
                    expect($test[0])->toEqual(null);

                });

            });

            context('when the parameter has a default value', function () {

                it('should return an empty array', function () {

                    $this->parameter->hasDefaultValue->returns(true);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toEqual([]);

                });

            });

            context('when the parameter is variadic', function () {

                it('should return an empty array', function () {

                    $this->parameter->isVariadic->returns(true);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toEqual([]);

                });

            });

        });

        context('when the parameter does not allow null', function () {

            it('should return an empty array', function () {

                $this->parameter->allowsNull->returns(false);

                $test = $this->pool->arguments($this->parameter->get());

                expect($test)->toEqual([]);

            });

        });

    });

});
