<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\DefaultArgumentPool;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\ContainerErrorMessage;
use Quanta\DI\Arguments\VariadicErrorMessage;

use Quanta\DI\Parameters\ParameterInterface;

describe('DefaultArgumentPool', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

        $this->pool = new DefaultArgumentPool($this->container->get(), [
            '$p1' => '@' . SomeClass1::class,
            '$p2' => 'value',
            '$p3' => ['value1', 'value2', 'value3'],
            AliasedInsterface1::class => '@' . SomeClass2::class,
            AliasedInsterface2::class => $this->instance = new class {},
            AliasedInsterface3::class => [
                $this->instance1 = new class {},
                $this->instance2 = new class {},
                $this->instance3 = new class {},
            ],
        ]);

    });

    it('should implement ArgumentPoolInterface', function () {

        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->arguments()', function () {

        beforeEach(function () {

            $this->parameter = mock(ParameterInterface::class);

        });

        context('when the parameter name is in the options array keys', function () {

            context('when the associated value starts with @', function () {

                beforeEach(function () {

                    $this->parameter->name->returns('$p1');

                });

                context('when the container does not fail to retrieve the entry associated with the alias', function () {

                    context('when the parameter is not variadic', function () {

                        it('should return an array containing the container entry associated with the alias', function () {

                            $instance = new class {};

                            $this->container->get->with(SomeClass1::class)->returns($instance);

                            $this->parameter->isVariadic->returns(false);

                            $test = $this->pool->arguments($this->parameter->get());

                            expect($test)->toBeAn('array');
                            expect($test)->toHaveLength(1);
                            expect($test[0])->toBe($instance);

                        });

                    });

                    context('when the parameter is variadic', function () {

                        beforeEach(function () {

                            $this->parameter->isVariadic->returns(true);

                        });

                        context('when the container entry is an array', function () {

                            it('should return the container entry', function () {

                                $instance1 = new class {};
                                $instance2 = new class {};
                                $instance3 = new class {};

                                $this->container->get->with(SomeClass1::class)->returns([
                                    $instance1,
                                    $instance2,
                                    $instance3,
                                ]);

                                $test = $this->pool->arguments($this->parameter->get());

                                expect($test)->toBeAn('array');
                                expect($test)->toHaveLength(3);
                                expect($test[0])->toBe($instance1);
                                expect($test[1])->toBe($instance2);
                                expect($test[2])->toBe($instance3);

                            });

                        });

                        context('when the container entry is not an array', function () {

                            it('should throw a LogicException', function () {

                                $instance = new class {};

                                $this->container->get->with(SomeClass1::class)->returns($instance);

                                $test = function () {
                                    $this->pool->arguments($this->parameter->get());
                                };

                                expect($test)->toThrow(new LogicException(
                                    (string) new VariadicErrorMessage($this->parameter->get(), $instance)
                                ));

                            });

                        });

                    });

                });

                context('when the container fails to retrieve the entry associated with the alias', function () {

                    it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                        $exception = mock(Throwable::class);

                        $this->container->get->with(SomeClass1::class)->throws($exception);

                        $test = function () {
                            $this->pool->arguments($this->parameter->get());
                        };

                        expect($test)->toThrow(new LogicException(
                            (string) new ContainerErrorMessage($this->parameter->get(), SomeClass1::class),
                            0,
                            $exception->get()
                        ));

                    });

                });

            });

            context('when the associated value does not start with @', function () {

                context('when the parameter is not variadic', function () {

                    it('should return an array containing the associated value', function () {

                        $this->parameter->name->returns('$p2');
                        $this->parameter->isVariadic->returns(false);

                        $test = $this->pool->arguments($this->parameter->get());

                        expect($test)->toBeAn('array');
                        expect($test)->toHaveLength(1);
                        expect($test[0])->toEqual('value');

                    });

                });

                context('when the parameter is variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(true);

                    });

                    context('when the value associated with the parameter is an array', function () {

                        it('should return the value', function () {

                            $this->parameter->name->returns('$p3');

                            $test = $this->pool->arguments($this->parameter->get());

                            expect($test)->toBeAn('array');
                            expect($test)->toHaveLength(3);
                            expect($test[0])->toEqual('value1');
                            expect($test[1])->toEqual('value2');
                            expect($test[2])->toEqual('value3');

                        });

                    });

                    context('when the value associated with the parameter is not an array', function () {

                        it('should throw a LogicException', function () {

                            $this->parameter->name->returns('$p2');

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

        });

        context('when the parameter name is not in the options array keys', function () {

            beforeEach(function () {

                $this->parameter->name->returns('$p4');

            });

            context('when the parameter has a class name', function () {

                beforeEach(function () {

                    $this->parameter->hasClassTypeHint->returns(true);

                });

                context('when the parameter class name is in the options array keys', function () {

                    context('when the associated value starts with @', function () {

                        beforeEach(function () {

                            $this->parameter->typeHint->returns(AliasedInsterface1::class);

                        });

                        context('when the container does not fail to retrieve the entry associated with the alias', function () {

                            context('when the parameter is not variadic', function () {

                                it('should return an array containing the container entry associated with the alias', function () {

                                    $instance = new class {};

                                    $this->container->get->with(SomeClass2::class)->returns($instance);

                                    $this->parameter->isVariadic->returns(false);

                                    $test = $this->pool->arguments($this->parameter->get());

                                    expect($test)->toBeAn('array');
                                    expect($test)->toHaveLength(1);
                                    expect($test[0])->toBe($instance);

                                });

                            });

                            context('when the parameter is variadic', function () {

                                beforeEach(function () {

                                    $this->parameter->isVariadic->returns(true);

                                });

                                context('when the container entry is an array', function () {

                                    it('should return the container entry', function () {

                                        $instance1 = new class {};
                                        $instance2 = new class {};
                                        $instance3 = new class {};

                                        $this->container->get->with(SomeClass2::class)->returns([
                                            $instance1,
                                            $instance2,
                                            $instance3,
                                        ]);

                                        $test = $this->pool->arguments($this->parameter->get());

                                        expect($test)->toBeAn('array');
                                        expect($test)->toHaveLength(3);
                                        expect($test[0])->toBe($instance1);
                                        expect($test[1])->toBe($instance2);
                                        expect($test[2])->toBe($instance3);

                                    });

                                });

                                context('when the container entry is not an array', function () {

                                    it('should throw a LogicException', function () {

                                        $instance = new class {};

                                        $this->container->get->with(SomeClass2::class)->returns($instance);

                                        $test = function () {
                                            $this->pool->arguments($this->parameter->get());
                                        };

                                        expect($test)->toThrow(new LogicException(
                                            (string) new VariadicErrorMessage($this->parameter->get(), $instance)
                                        ));

                                    });

                                });

                            });

                        });

                        context('when the container fails to retrieve the entry associated with the alias', function () {

                            it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                                $exception = mock(Throwable::class);

                                $this->container->get->with(SomeClass2::class)->throws($exception);

                                $test = function () {
                                    $this->pool->arguments($this->parameter->get());
                                };

                                expect($test)->toThrow(new LogicException(
                                    (string) new ContainerErrorMessage($this->parameter->get(), SomeClass2::class),
                                    0,
                                    $exception->get()
                                ));

                            });

                        });

                    });

                    context('when the associated value does not start with @', function () {

                        context('when the parameter is not variadic', function () {

                            it('should return an array containing the associated value', function () {

                                $this->parameter->typeHint->returns(AliasedInsterface2::class);
                                $this->parameter->isVariadic->returns(false);

                                $test = $this->pool->arguments($this->parameter->get());

                                expect($test)->toBeAn('array');
                                expect($test)->toHaveLength(1);
                                expect($test[0])->toEqual($this->instance);

                            });

                        });

                        context('when the parameter is variadic', function () {

                            beforeEach(function () {

                                $this->parameter->isVariadic->returns(true);

                            });

                            context('when the value associated with the parameter is an array', function () {

                                it('should return the value', function () {

                                    $this->parameter->typeHint->returns(AliasedInsterface3::class);

                                    $test = $this->pool->arguments($this->parameter->get());

                                    expect($test)->toBeAn('array');
                                    expect($test)->toHaveLength(3);
                                    expect($test[0])->toBe($this->instance1);
                                    expect($test[1])->toBe($this->instance2);
                                    expect($test[2])->toBe($this->instance3);

                                });

                            });

                            context('when the value associated with the parameter is not an array', function () {

                                it('should throw a LogicException', function () {

                                    $this->parameter->typeHint->returns(AliasedInsterface2::class);

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

                });

                context('when the parameter class name is not in the option array key', function () {

                    beforeEach(function () {

                        $this->parameter->typeHint->returns(SomeClass3::class);

                    });

                    context('when the container has an entry for the parameter class name', function () {

                        beforeEach(function () {

                            $this->container->has->with(SomeClass3::class)->returns(true);

                        });

                        context('when the container does not fail to retrieve the entry associated with the alias', function () {

                            context('when the parameter is not variadic', function () {

                                it('should return an array containing the container entry associated with the alias', function () {

                                    $instance = new class {};

                                    $this->container->get->with(SomeClass3::class)->returns($instance);

                                    $this->parameter->isVariadic->returns(false);

                                    $test = $this->pool->arguments($this->parameter->get());

                                    expect($test)->toBeAn('array');
                                    expect($test)->toHaveLength(1);
                                    expect($test[0])->toBe($instance);

                                });

                            });

                            context('when the parameter is variadic', function () {

                                it('should return an empty array', function () {

                                    $this->container->has->with(SomeClass3::class)->returns(false);

                                    $this->parameter->isVariadic->returns(true);

                                    $test = $this->pool->arguments($this->parameter->get());

                                    expect($test)->toBeAn('array');
                                    expect($test)->toHaveLength(0);

                                });

                            });

                        });

                        context('when the container fails to retrieve the entry associated with the alias', function () {

                            it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                                $exception = mock(Throwable::class);

                                $this->container->get->with(SomeClass3::class)->throws($exception);

                                $test = function () {
                                    $this->pool->arguments($this->parameter->get());
                                };

                                expect($test)->toThrow(new LogicException(
                                    (string) new ContainerErrorMessage($this->parameter->get(), SomeClass3::class),
                                    0,
                                    $exception->get()
                                ));

                            });

                        });

                    });

                    context('when the container does not have an entry for the parameter class name', function () {

                        it('should return an empty array', function () {

                            $this->container->has->with(SomeClass3::class)->returns(false);

                            $test = $this->pool->arguments($this->parameter->get());

                            expect($test)->toBeAn('array');
                            expect($test)->toHaveLength(0);

                        });

                    });

                });

            });

            context('when the container does not have a class name', function () {

                it('should return an empty array', function () {

                    $this->parameter->hasClassTypeHint->returns(false);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(0);

                });

            });

        });

    });

});
