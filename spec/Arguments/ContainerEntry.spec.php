<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Quanta\PA\CallableInterface;
use Quanta\PA\CallableWithArgument;
use Quanta\DI\Arguments\ContainerEntry;
use Quanta\DI\Arguments\ArgumentInterface;

describe('ContainerEntry', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

    });

    context('when the container entry is not nullable', function () {

        beforeEach(function () {

            $this->argument = new ContainerEntry($this->container->get(), ...[
                'id',
                false,
            ]);

        });

        it('should implement ArgumentInterface', function () {

            expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

        });

        describe('->isBound()', function () {

            it('should return true', function () {

                $test = $this->argument->isBound();

                expect($test)->toBeTruthy();

            });

        });

        describe('->bound()', function () {

            beforeEach(function () {

                $this->callable = mock(CallableInterface::class);

            });

            context('when the container entry is defined', function () {

                beforeEach(function () {

                    $this->container->has->with('id')->returns(true);

                });

                context('when the container ->get() method does not throw a not found exception', function () {

                    it('should bind the given callable to the container entry', function () {

                        $this->container->get->with('id')->returns('value');

                        $test = $this->argument->bound($this->callable->get());

                        expect($test)->toEqual(new CallableWithArgument($this->callable->get(), ...[
                            'value',
                        ]));

                    });

                });

                context('when the container ->get() method throws a not found exception', function () {

                    it('should propagate the exception', function () {

                        $exception = mock([Throwable::class, NotFoundExceptionInterface::class]);

                        $this->container->get->with('id')->throws($exception);

                        $test = function () {
                            $this->argument->bound($this->callable->get());
                        };

                        expect($test)->toThrow($exception->get());

                    });

                });

            });

            context('when the container entry is not defined', function () {

                it('should try to retrieve the container entry anyway', function () {

                    $this->container->has->with('id')->returns(false);

                    $this->argument->bound($this->callable->get());

                    $this->container->get->once()->calledWith('id');

                });

            });

        });

    });

    context('when the container entry is nullable', function () {

        beforeEach(function () {

            $this->argument = new ContainerEntry($this->container->get(), ...[
                'id',
                true,
            ]);

        });

        it('should implement ArgumentInterface', function () {

            expect($this->argument)->toBeAnInstanceOf(ArgumentInterface::class);

        });

        describe('->isBound()', function () {

            it('should return true', function () {

                $test = $this->argument->isBound();

                expect($test)->toBeTruthy();

            });

        });

        describe('->bound()', function () {

            beforeEach(function () {

                $this->callable = mock(CallableInterface::class);

            });

            context('when the container entry is defined', function () {

                beforeEach(function () {

                    $this->container->has->with('id')->returns(true);

                });

                context('when the container ->get() method does not throw a not found exception', function () {

                    it('should bind the given callable to the container entry', function () {

                        $this->container->get->with('id')->returns('value');

                        $test = $this->argument->bound($this->callable->get());

                        expect($test)->toEqual(new CallableWithArgument($this->callable->get(), ...[
                            'value',
                        ]));

                    });

                });

                context('when the container ->get() method throws a not found exception', function () {

                    it('should bind the given callable to null', function () {

                        $exception = mock([Throwable::class, NotFoundExceptionInterface::class]);

                        $this->container->get->with('id')->throws($exception);

                        $test = $this->argument->bound($this->callable->get());

                        expect($test)->toEqual(new CallableWithArgument($this->callable->get(), ...[
                            null,
                        ]));

                    });

                });

            });

            context('when the container entry is not defined', function () {

                it('should bind the given callable to null', function () {

                    $this->container->has->with('id')->returns(false);

                    $test = $this->argument->bound($this->callable->get());

                    expect($test)->toEqual(new CallableWithArgument($this->callable->get(), ...[
                        null,
                    ]));

                });

            });

        });

    });

});
