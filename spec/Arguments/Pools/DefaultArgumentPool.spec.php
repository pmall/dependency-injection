<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\Argument;
use Quanta\DI\Arguments\Pools\DefaultArgumentPool;
use Quanta\DI\Arguments\Pools\ArgumentPoolInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\Exceptions\ArrayArgumentTypeErrorMessage;

describe('CompositeArgumentPool', function () {

    context('when all values of the given array of aliases are strings', function () {

        beforeEach(function () {

            $this->pool = new DefaultArgumentPool([
                '$p1' => SomeClass1::class,
                '$p2' => SomeClass2::class,
                AliasedClass1::class => 'id1',
                AliasedClass2::class => 'id2',
            ], [
                '$p2' => 'value1',
                AliasedClass2::class => 'value2',
            ]);

        });

        it('should implement ArgumentPoolInterface', function () {

            expect($this->pool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

        });

        describe('->argument()', function () {

            beforeEach(function () {

                $this->container = mock(ContainerInterface::class);
                $this->parameter = mock(ParameterInterface::class);

            });

            context('when the parameter name is only in the alias map', function () {

                it('should return the value of the alias associated with the parameter name', function () {

                    $this->parameter->name->returns('p1');
                    $this->container->get->with(SomeClass1::class)->returns('value');

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value'));

                });

            });

            context('when the parameter name is both in the alias map and in the value map', function () {

                it('should return the value associated with the parameter name', function () {

                    $this->parameter->name->returns('p2');

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value1'));

                });

            });

            context('when the parameter class name is only in the alias map', function () {

                it('should return the value of the alias associated with the parameter class name', function () {

                    $this->parameter->name->returns('p3');
                    $this->parameter->hasClassTypeHint->returns(true);
                    $this->parameter->typeHint->returns(AliasedClass1::class);
                    $this->container->get->with('id1')->returns('value');

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value'));

                });

            });

            context('when the parameter class name is both in the alias map and in the value map', function () {

                it('should return the value associated with the parameter class name', function () {

                    $this->parameter->name->returns('p3');
                    $this->parameter->hasClassTypeHint->returns(true);
                    $this->parameter->typeHint->returns(AliasedClass2::class);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value2'));

                });

            });

            context('when both the parameter name and the parameter class name is in the alias map', function () {

                it('should return the value of the alias associated with the parameter name', function () {

                    $this->parameter->name->returns('p1');
                    $this->parameter->hasClassTypeHint->returns(true);
                    $this->parameter->typeHint->returns(AliasedClass1::class);
                    $this->container->get->with(SomeClass1::class)->returns('value');

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value'));

                });

            });

            context('when both the parameter name and the parameter class name is in the value map', function () {

                it('should return the value associated with the parameter name', function () {

                    $this->parameter->name->returns('p2');
                    $this->parameter->hasClassTypeHint->returns(true);
                    $this->parameter->typeHint->returns(AliasedClass2::class);

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value1'));

                });

            });

            context('when the parameter name and class name are not in the alias map and the values map',function () {

                it('should return the value provided by the container', function () {

                    $this->parameter->hasClassTypeHint->returns(true);
                    $this->parameter->typeHint->returns(AliasedClass::class);
                    $this->container->has->with(AliasedClass::class)->returns(true);
                    $this->container->get->with(AliasedClass::class)->returns('value');

                    $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                    expect($test)->toEqual(new Argument('value'));

                });

            });

        });

    });

    context('when at least one value of the given array of aliases is not a string', function () {

        it('should throw an InvalidArgumentException', function () {

            ArrayArgumentTypeErrorMessage::testing();

            $aliases = [
                '$p1' => SomeClass1::class,
                '$p2' => 1,
                '$p3' => SomeClass2::class,
            ];

            $test = function () use ($aliases) {
                new DefaultArgumentPool($aliases);
            };

            expect($test)->toThrow(new InvalidArgumentException(
                (string) new ArrayArgumentTypeErrorMessage(1, 'string', $aliases)
            ));

        });

    });

});
