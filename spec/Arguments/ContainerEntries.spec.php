<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ContainerEntry;
use Quanta\DI\Arguments\UnboundArgument;
use Quanta\DI\Arguments\ContainerEntries;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Parameters\TypeHint;
use Quanta\DI\Parameters\ParameterInterface;

use Quanta\Exceptions\ArrayArgumentTypeErrorMessage;

describe('ContainerEntries', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

    });

    context('when there is no name to alias map', function () {

        beforeEach(function () {

            $this->pool = new ContainerEntries($this->container->get());

        });

        it('should implement ArgumentPoolInterface', function () {

            expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

        });

        describe('->argument()', function () {

            beforeEach(function () {

                $this->parameter = mock(ParameterInterface::class);

            });

            context('when the given parameter has a type hint', function () {

                beforeEach(function () {

                    $this->parameter->hasTypeHint->returns(true);

                });

                context('when the type hint is nullable', function () {

                    it('should return a nullable container entry with the type hint', function () {

                        $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, true));

                        $test = $this->pool->argument($this->parameter->get());

                        expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                            SomeClass::class,
                            true,
                        ]));

                    });

                });

                context('when the type hint is not nullable', function () {

                    it('should return a non nullable container entry with the type hint', function () {

                        $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, false));

                        $test = $this->pool->argument($this->parameter->get());

                        expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                            SomeClass::class,
                            false,
                        ]));

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

    context('when there is a name to alias map', function () {

        context('when all the values of the name to alias map are strings', function () {

            context('when there is no type hint to alias map', function () {

                beforeEach(function () {

                    $this->pool = new ContainerEntries($this->container->get(), [
                        'parameter1' => 'id1',
                        'parameter2' => 'id2',
                        'parameter3' => 'id3',
                    ]);

                });

                it('should implement ArgumentPoolInterface', function () {

                    expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

                });

                describe('->argument()', function () {

                    beforeEach(function () {

                        $this->parameter = mock(ParameterInterface::class);

                    });

                    context('when the given parameter has a type hint', function () {

                        beforeEach(function () {

                            $this->parameter->hasTypeHint->returns(true);

                        });

                        context('when the name of the given parameter is in the name to alias map', function () {

                            beforeEach(function () {

                                $this->parameter->name->returns('parameter2');

                            });

                            context('when the type hint is nullable', function () {

                                it('should return a non nullable container entry with the alias', function () {

                                    $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, true));

                                    $test = $this->pool->argument($this->parameter->get());

                                    expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                        'id2',
                                        false,
                                    ]));

                                });

                            });

                            context('when the type hint is not nullable', function () {

                                it('should return a non nullable container entry with the alias', function () {

                                    $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, false));

                                    $test = $this->pool->argument($this->parameter->get());

                                    expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                        'id2',
                                        false,
                                    ]));

                                });

                            });

                        });

                        context('when the name of the given parameter is not in the name to alias map', function () {

                            beforeEach(function () {

                                $this->parameter->name->returns('parameter4');

                            });

                            context('when the type hint is nullable', function () {

                                it('should return a nullable container entry with the type hint', function () {

                                    $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, true));

                                    $test = $this->pool->argument($this->parameter->get());

                                    expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                        SomeClass::class,
                                        true,
                                    ]));

                                });

                            });

                            context('when the type hint is not nullable', function () {

                                it('should return a non nullable container entry with the type hint', function () {

                                    $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, false));

                                    $test = $this->pool->argument($this->parameter->get());

                                    expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                        SomeClass::class,
                                        false,
                                    ]));

                                });

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

            context('when there is a type hint to alias map', function () {

                context('when all the values of the type hint to alias map are strings', function () {

                    beforeEach(function () {

                        $this->pool = new ContainerEntries($this->container->get(), [
                            'parameter1' => 'id1',
                            'parameter2' => 'id2',
                            'parameter3' => 'id3',
                        ], [
                            SomeClass1::class => 'id4',
                            SomeClass2::class => 'id5',
                            SomeClass3::class => 'id6',
                        ]);

                    });

                    it('should implement ArgumentPoolInterface', function () {

                        expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

                    });

                    describe('->argument()', function () {

                        beforeEach(function () {

                            $this->parameter = mock(ParameterInterface::class);

                        });

                        context('when the given parameter has a type hint', function () {

                            beforeEach(function () {

                                $this->parameter->hasTypeHint->returns(true);

                            });

                            context('when the name of the given parameter is in the name to alias map', function () {

                                beforeEach(function () {

                                    $this->parameter->name->returns('parameter2');

                                });

                                context('when the type hint is nullable', function () {

                                    it('should return a non nullable container entry with the alias', function () {

                                        $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, true));

                                        $test = $this->pool->argument($this->parameter->get());

                                        expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                            'id2',
                                            false,
                                        ]));

                                    });

                                });

                                context('when the type hint is not nullable', function () {

                                    it('should return a non nullable container entry with the alias', function () {

                                        $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, false));

                                        $test = $this->pool->argument($this->parameter->get());

                                        expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                            'id2',
                                            false,
                                        ]));

                                    });

                                });

                            });

                            context('when the name of the given parameter is not in the name to alias map', function () {

                                beforeEach(function () {

                                    $this->parameter->name->returns('parameter4');

                                });

                                context('when the type hint of the given parameter is in the type hint to alias map', function () {

                                    context('when the type hint is nullable', function () {

                                        it('should return a non nullable container entry with the alias', function () {

                                            $this->parameter->typeHint->returns(new TypeHint(SomeClass2::class, true));

                                            $test = $this->pool->argument($this->parameter->get());

                                            expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                                'id5',
                                                false,
                                            ]));

                                        });

                                    });

                                    context('when the type hint is not nullable', function () {

                                        it('should return a non nullable container entry with the alias', function () {

                                            $this->parameter->typeHint->returns(new TypeHint(SomeClass2::class, false));

                                            $test = $this->pool->argument($this->parameter->get());

                                            expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                                'id5',
                                                false,
                                            ]));

                                        });

                                    });

                                });

                                context('when the type hint of the given parameter is not in the type hint to alias map', function () {

                                    context('when the type hint is nullable', function () {

                                        it('should return a nullable container entry with the type hint', function () {

                                            $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, true));

                                            $test = $this->pool->argument($this->parameter->get());

                                            expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                                SomeClass::class,
                                                true,
                                            ]));

                                        });

                                    });

                                    context('when the type hint is not nullable', function () {

                                        it('should return a non nullable container entry with the type hint', function () {

                                            $this->parameter->typeHint->returns(new TypeHint(SomeClass::class, false));

                                            $test = $this->pool->argument($this->parameter->get());

                                            expect($test)->toEqual(new ContainerEntry($this->container->get(), ...[
                                                SomeClass::class,
                                                false,
                                            ]));

                                        });

                                    });

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

                context('when a value of the type hint to alias map is not a string', function () {

                    it('should throw an InvalidArgumentException', function () {

                        ArrayArgumentTypeErrorMessage::testing();

                        $types = [
                            SomeClass1::class => 'id4',
                            SomeClass2::class => [],
                            SomeClass3::class => 'id6',
                        ];

                        $test = function () use ($types) {
                            new ContainerEntries($this->container->get(), [
                                'parameter1' => 'id1',
                                'parameter2' => 'id2',
                                'parameter3' => 'id3',
                            ], $types);
                        };

                        expect($test)->toThrow(new InvalidArgumentException(
                            (string) new ArrayArgumentTypeErrorMessage(3, 'string', $types)
                        ));

                    });

                });

            });

        });

        context('when a value of the name to alias map is not a string', function () {

            it('should throw an InvalidArgumentException', function () {

                ArrayArgumentTypeErrorMessage::testing();

                $names = [
                    'parameter1' => 'id1',
                    'parameter2' => [],
                    'parameter3' => 'id3',
                ];

                $test = function () use ($names) {
                    new ContainerEntries($this->container->get(), $names);
                };

                expect($test)->toThrow(new InvalidArgumentException(
                    (string) new ArrayArgumentTypeErrorMessage(2, 'string', $names)
                ));

            });

        });

    });

});
