<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Quanta\DependencyInjection\Arguments\Argument;
use Quanta\DependencyInjection\Arguments\Placeholder;
use Quanta\DependencyInjection\Arguments\VariadicArgument;
use Quanta\DependencyInjection\Parameters\ParameterInterface;
use Quanta\DependencyInjection\Arguments\Pools\NameValueMap;
use Quanta\DependencyInjection\Arguments\Pools\ArgumentPoolInterface;

describe('NameValueMap', function () {

    beforeEach(function () {

        $this->pool = new NameValueMap([
            'name1' => 'value',
            'name2' => ['value1', 'value2', 'value3'],
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

        context('when the given parameter name is in the map', function () {

            context('when the given parameter is not variadic', function () {

                beforeEach(function () {

                    $this->parameter->isVariadic->returns(false);

                });

                context('when the value associated to the parameter is not an array', function () {

                    it('should return an Argument containing the value', function () {

                        $this->parameter->name->returns('name1');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Argument('value'));

                    });

                });

                context('when the value associated to the parameter is an array', function () {

                    it('should return an Argument containing the array', function () {

                        $this->parameter->name->returns('name2');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new Argument(['value1', 'value2', 'value3']));

                    });

                });

            });

            context('when the given parameter is variadic', function () {

                beforeEach(function () {

                    $this->parameter->isVariadic->returns(true);

                });

                context('when the value associated to the parameter is an array', function () {

                    it('should return a VariadicArgument containing the value', function () {

                        $this->parameter->name->returns('name2');

                        $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                        expect($test)->toEqual(new VariadicArgument(['value1', 'value2', 'value3']));

                    });

                });

                context('when the value associated to the parameter is not an array', function () {

                    it('it should throw a logic exception', function () {

                        $this->parameter->name->returns('name1');

                        $test = function () {
                            $this->pool->argument($this->container->get(), $this->parameter->get());
                        };

                        expect($test)->toThrow(new LogicException);

                    });

                });

            });

        });

        context('when the given parameter name is not in the map', function () {

            it('should return a placeholder', function () {

                $this->parameter->name->returns('name3');

                $test = $this->pool->argument($this->container->get(), $this->parameter->get());

                expect($test)->toEqual(new Placeholder);

            });

        });

    });

});
