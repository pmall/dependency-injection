<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Arguments\TypeHintInstanceMap;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\VariadicErrorMessage;

use Quanta\DI\Parameters\ParameterInterface;

describe('TypeHintInstanceMap', function () {

    beforeEach(function () {

        $this->instance = new class {};
        $this->instance1 = new class {};
        $this->instance2 = new class {};
        $this->instance3 = new class {};

        $this->pool = new TypeHintInstanceMap([
            SomeClass1::class => $this->instance,
            SomeClass2::class => [$this->instance1, $this->instance2, $this->instance3],
        ]);

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->arguments()', function () {

        beforeEach(function () {

            $this->parameter = mock(ParameterInterface::class);

            $this->parameter->name->returns('$name');

        });

        context('when the parameter has a class type hint', function () {

            beforeEach(function () {

                $this->parameter->hasClassTypeHint->returns(true);

            });

            context('when the given parameter class name is in the map', function () {

                context('when the given parameter is not variadic', function () {

                    it('should return an array containing the object', function () {

                        $this->parameter->typeHint->returns(SomeClass1::class);
                        $this->parameter->isVariadic->returns(false);

                        $test = $this->pool->arguments($this->parameter->get());

                        expect($test)->toBeAn('array');
                        expect($test)->toHaveLength(1);
                        expect($test[0])->toBe($this->instance);

                    });

                });

                context('when the given parameter is variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(true);

                    });

                    context('when the value associated to the parameter is an array', function () {

                        it('should return the value', function () {

                            $this->parameter->typeHint->returns(SomeClass2::class);

                            $test = $this->pool->arguments($this->parameter->get());

                            expect($test)->toBeAn('array');
                            expect($test)->toHaveLength(3);
                            expect($test[0])->toBe($this->instance1);
                            expect($test[1])->toBe($this->instance2);
                            expect($test[2])->toBe($this->instance3);

                        });

                    });

                    context('when the value associated to the parameter is not an array', function () {

                        it('it should throw a LogicException', function () {

                            $this->parameter->typeHint->returns(SomeClass1::class);

                            $test = function () {
                                $this->pool->arguments($this->parameter->get());
                            };

                            expect($test)->toThrow(new LogicException(
                                (string) new VariadicErrorMessage($this->parameter->get(), $this->instance)
                            ));

                        });

                    });

                });

            });

            context('when the given parameter class name is not in the map', function () {

                it('should return an empty array', function () {

                    $this->parameter->typeHint->returns(SomeClass3::class);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(0);

                });

            });

        });

        context('when the parameter does not have a class type hint', function () {

            it('should return an empty array', function () {

                $this->parameter->hasClassTypeHint->returns(false);

                $test = $this->pool->arguments($this->parameter->get());

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(0);

            });

        });

    });

});
