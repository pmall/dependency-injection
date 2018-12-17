<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Arguments\NameValueMap;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\VariadicErrorMessage;

use Quanta\DI\Parameters\ParameterInterface;

describe('NameValueMap', function () {

    beforeEach(function () {

        $this->pool = new NameValueMap([
            '$name1' => 'value',
            '$name2' => ['value1', 'value2', 'value3'],
        ]);

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->arguments()', function () {

        beforeEach(function () {

            $this->parameter = mock(ParameterInterface::class);

        });

        context('when the given parameter name is in the map', function () {

            context('when the given parameter is not variadic', function () {

                it('should return an array containing the value', function () {

                    $this->parameter->name->returns('$name1');
                    $this->parameter->isVariadic->returns(false);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(1);
                    expect($test[0])->toEqual('value');

                });

            });

            context('when the given parameter is variadic', function () {

                beforeEach(function () {

                    $this->parameter->isVariadic->returns(true);

                });

                context('when the value associated to the parameter is an array', function () {

                    it('should return the value', function () {

                        $this->parameter->name->returns('$name2');

                        $test = $this->pool->arguments($this->parameter->get());

                        expect($test)->toBeAn('array');
                        expect($test)->toHaveLength(3);
                        expect($test[0])->toEqual('value1');
                        expect($test[1])->toEqual('value2');
                        expect($test[2])->toEqual('value3');

                    });

                });

                context('when the value associated to the parameter is not an array', function () {

                    it('it should throw a LogicException', function () {

                        $this->parameter->name->returns('$name1');

                        $test = function () {
                            $this->pool->arguments($this->parameter->get());
                        };

                        expect($test)->toThrow(new LogicException(
                            (string) new VariadicErrorMessage($this->parameter->get(), 'value')
                        ));

                    });

                });

            });

        });

        context('when the given parameter name is not in the map', function () {

            it('should return an empty array', function () {

                $this->parameter->name->returns('$name3');

                $test = $this->pool->arguments($this->parameter->get());

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(0);

            });

        });

    });

});
