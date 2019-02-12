<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\TypeHint;
use Quanta\DI\Parameters\OptionalParameter;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\TypeHintErrorMessage;

describe('OptionalParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(ParameterInterface::class);

    });

    context('when the default value is not null', function () {

        beforeEach(function () {

            $this->parameter = new OptionalParameter($this->delegate->get(), ...[
                'default',
            ]);

        });

        it('should implement ParameterInterface', function () {

            expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

        });

        describe('->name()', function () {

            it('should return the delegate name', function () {

                $this->delegate->name->returns('name');

                $test = $this->parameter->name();

                expect($test)->toEqual('name');

            });

        });

        describe('->hasTypeHint()', function () {

            it('should return false', function () {

                $test = $this->parameter->hasTypeHint();

                expect($test)->toBeFalsy();

            });

        });

        describe('->typeHint()', function () {

            it('should throw a LogicException', function () {

                expect([$this->parameter, 'typeHint'])->toThrow(new LogicException(
                    (string) new TypeHintErrorMessage($this->parameter)
                ));

            });

        });

        describe('->hasDefaultValue()', function () {

            it('should return true', function () {

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeTruthy();

            });

        });

        describe('->defaultValue()', function () {

            it('should return the default value', function () {

                $test = $this->parameter->defaultValue();

                expect($test)->toEqual('default');

            });

        });

    });

    context('when the default value is null', function () {

        beforeEach(function () {

            $this->parameter = new OptionalParameter($this->delegate->get(), ...[
                null,
            ]);

        });

        it('should implement ParameterInterface', function () {

            expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

        });

        describe('->name()', function () {

            it('should return the delegate name', function () {

                $this->delegate->name->returns('name');

                $test = $this->parameter->name();

                expect($test)->toEqual('name');

            });

        });

        describe('->hasTypeHint()', function () {

            context('when the delegate has a type hint', function () {

                it('should return true', function () {

                    $this->delegate->hasTypeHint->returns(true);

                    $test = $this->parameter->hasTypeHint();

                    expect($test)->toBeTruthy();

                });

            });

            context('when the delegate does not have a type hint', function () {

                it('should return false', function () {

                    $this->delegate->hasTypeHint->returns(false);

                    $test = $this->parameter->hasTypeHint();

                    expect($test)->toBeFalsy();

                });

            });

        });

        describe('->typeHint()', function () {

            it('should return the delegate type hint', function () {

                $type = new TypeHint(SomeClass::class, true);

                $this->delegate->typeHint->returns($type);

                $test = $this->parameter->typeHint();

                expect($test)->toBe($type);

            });

        });

        describe('->hasDefaultValue()', function () {

            it('should return true', function () {

                $test = $this->parameter->hasDefaultValue();

                expect($test)->toBeTruthy();

            });

        });

        describe('->defaultValue()', function () {

            it('should return null', function () {

                $test = $this->parameter->defaultValue();

                expect($test)->toBeNull();

            });

        });

    });

});
