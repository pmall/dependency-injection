<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\NameAliasMap;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\ContainerErrorMessage;
use Quanta\DI\Arguments\VariadicErrorMessage;

use Quanta\DI\Parameters\ParameterInterface;

describe('NameAliasMap', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

        $this->pool = new NameAliasMap($this->container->get(), [
            '$name1' => SomeClass::class,
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

            beforeEach(function () {

                $this->parameter->name->returns('$name1');

            });

            context('when the container does not fail to retrieve the entry', function () {

                context('when the given parameter is not variadic', function () {

                    it('should return an array containing the container entry', function () {

                        $instance = new class {};

                        $this->container->get->with(SomeClass::class)->returns($instance);

                        $this->parameter->isVariadic->returns(false);

                        $test = $this->pool->arguments($this->parameter->get());

                        expect($test)->toBeAn('array');
                        expect($test)->toHaveLength(1);
                        expect($test[0])->toBe($instance);

                    });

                });

                context('when the given parameter is variadic', function () {

                    beforeEach(function () {

                        $this->parameter->isVariadic->returns(true);

                    });

                    context('when the container entry associated to the parameter is an array', function () {

                        it('should return the container entry', function () {

                            $instance1 = new class {};
                            $instance2 = new class {};
                            $instance3 = new class {};

                            $this->container->get->with(SomeClass::class)->returns([
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

                    context('when the container entry associated to the parameter is not an array', function () {

                        it('it should throw a LogicException', function () {

                            $this->container->get->with(SomeClass::class)->returns('value');

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

            context('when the container fails to retrieve the entry', function () {

                it('should throw a LogicException wrapped around the exception thrown by the container', function () {

                    $exception = mock(Throwable::class);

                    $this->container->get->with(SomeClass::class)->throws($exception);

                    $test = function () {
                        $this->pool->arguments($this->parameter->get());
                    };

                    expect($test)->toThrow(new LogicException(
                        (string) new ContainerErrorMessage($this->parameter->get(), SomeClass::class),
                        0,
                        $exception->get()
                    ));

                });

            });

        });

        context('when the given parameter name is not in the map', function () {

            it('should return an empty array', function () {

                $this->parameter->name->returns('$name2');

                $test = $this->pool->arguments($this->parameter->get());

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(0);

            });

        });

    });

});