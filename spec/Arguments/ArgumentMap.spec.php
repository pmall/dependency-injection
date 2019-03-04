<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\ArgumentMap;
use Quanta\DI\Arguments\UnboundArgument;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\TypeHint;
use Quanta\DI\Parameters\ParameterInterface;

describe('ArgumentMap', function () {

    context('when there is no type hint to instance map',function () {

        beforeEach(function () {

            $this->pool = new ArgumentMap([
                'parameter1' => 'argument1',
                'parameter2' => 'argument2',
                'parameter3' => 'argument3',
            ]);

        });

        it('should implement ArgumentPoolInterface', function () {

            expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

        });

        describe('->argument()', function () {

            beforeEach(function () {

                $this->parameter = mock(ParameterInterface::class);

            });

            context('when the name of the given parameter is in the name to argument map', function () {

                it('should return the argument associated to the name of the given parameter', function () {

                    $this->parameter->name->returns('parameter2');

                    $test = $this->pool->argument($this->parameter->get());

                    expect($test)->toEqual(new Argument('argument2'));

                });

            });

            context('when the name of the given parameter is not in the name to argument map', function () {

                beforeEach(function () {

                    $this->parameter->name->returns('parameter4');

                });

                context('when given the parameter has a type hint', function () {

                    it('should return an unbound argument', function () {

                        $this->parameter->hasTypeHint->returns(true);
                        $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, false));

                        $test = $this->pool->argument($this->parameter->get());

                        expect($test)->toEqual(new UnboundArgument);

                    });

                });

                context('when the given parameter does not have a type hint', function () {

                    it('should return an unbound argument', function () {

                        $this->parameter->hasTypeHint->returns(false);

                        $test = $this->pool->argument($this->parameter->get());

                        expect($test)->toEqual(new UnboundArgument);

                    });

                });

            });

        });

    });

    context('when there is a type hint to instance map', function () {

        context('when all the values of the type hint to instance map are objects', function () {

            beforeEach(function () {

                $this->pool = new ArgumentMap([
                    'parameter1' => 'argument1',
                    'parameter2' => 'argument2',
                    'parameter3' => 'argument3',
                ], [
                    SomeClass1::class => $this->instance1 = new class {},
                    SomeClass2::class => $this->instance2 = new class {},
                    SomeClass3::class => $this->instance3 = new class {},
                ]);

            });

            it('should implement ArgumentPoolInterface', function () {

                expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

            });

            describe('->argument()', function () {

                beforeEach(function () {

                    $this->parameter = mock(ParameterInterface::class);

                });

                context('when the name of the given parameter is in the name to argument map', function () {

                    it('should return the argument associated to the name of the given parameter', function () {

                        $this->parameter->name->returns('parameter2');

                        $test = $this->pool->argument($this->parameter->get());

                        expect($test)->toEqual(new Argument('argument2'));

                    });

                });

                context('when the name of the given parameter is not in the name to argument map', function () {

                    beforeEach(function () {

                        $this->parameter->name->returns('parameter4');

                    });

                    context('when given the parameter has a type hint', function () {

                        beforeEach(function () {

                            $this->parameter->hasTypeHint->returns(true);

                        });

                        context('when the given parameter type hint is in the type hint to instance map', function () {

                            it('should return the instance associated to the given parameter type hint', function () {

                                $this->parameter->typeHint->returns(new TypeHint(SomeClass2::class, false));

                                $test = $this->pool->argument($this->parameter->get());

                                expect($test)->toEqual(new Argument($this->instance2));

                            });

                        });

                        context('when the given parameter type hint is not in the type hint to instance map', function () {

                            it('should return an unbound argument', function () {

                                $this->parameter->typeHint->returns(new TypeHint(SomeClass4::class, false));

                                $test = $this->pool->argument($this->parameter->get());

                                expect($test)->toEqual(new UnboundArgument);

                            });

                        });

                    });

                    context('when the given parameter does not have a type hint', function () {

                        it('should return an unbound argument', function () {

                            $this->parameter->hasTypeHint->returns(false);

                            $test = $this->pool->argument($this->parameter->get());

                            expect($test)->toEqual(new UnboundArgument);

                        });

                    });

                });

            });

        });

        context('when a value of the type hint to instance map is not an object', function () {

            it('should throw an InvalidArgumentException', function () {

                $test = function () {
                    new ArgumentMap([
                        'parameter1' => 'argument1',
                        'parameter2' => 'argument2',
                        'parameter3' => 'argument3',
                    ], [
                        SomeClass1::class => new class {},
                        SomeClass2::class => 'instance2',
                        SomeClass3::class => new class {},
                    ]);
                };

                expect($test)->toThrow(new InvalidArgumentException);

            });

        });

    });

});
