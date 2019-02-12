<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\PA\CallableWithPlaceholder;
use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Signatures\SignatureInterface;
use Quanta\DI\Signatures\SignatureWithParameter;
use Quanta\DI\Parameters\ParameterInterface;

describe('SignatureWithParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(SignatureInterface::class);
        $this->parameter = mock(ParameterInterface::class);

        $this->signature = new SignatureWithParameter(
            $this->delegate->get(),
            $this->parameter->get()
        );

    });

    it('should implement SignatureInterface', function () {

        expect($this->signature)->toBeAnInstanceOf(SignatureInterface::class);

    });

    describe('->bound()', function () {

        beforeEach(function () {

            $this->pool = mock(ArgumentPoolInterface::class);

            $this->callable = mock(CallableInterface::class);
            $this->argument = mock(ArgumentInterface::class);

            $this->delegate->bound->with($this->pool)->returns($this->callable);
            $this->pool->argument->with($this->parameter)->returns($this->argument);

        });

        context('when the argument from the argument pool is bound', function () {

            it('should return the callable bound by the argument', function () {

                $callable = mock(CallableInterface::class);

                $this->argument->isBound->returns(true);

                $this->argument->bound->with($this->callable)->returns($callable);

                $test = $this->signature->bound($this->pool->get());

                expect($test)->toBe($callable->get());

            });

        });

        context('when the argument from the argument pool is not bound', function () {

            beforeEach(function () {

                $this->argument->isBound->returns(false);

                $this->parameter->name->returns('parameter');

            });

            context('when the parameter has a default value', function () {

                it('should bind the callable to a placeholder with a default value', function () {

                    $this->parameter->hasDefaultValue->returns(true);
                    $this->parameter->defaultValue->returns('default');

                    $test = $this->signature->bound($this->pool->get());

                    expect($test)->toEqual(new CallableWithPlaceholder(
                        $this->callable->get(),
                        'parameter',
                        'default'
                    ));

                });

            });

            context('when the parameter does not have a default value', function () {

                it('should bind the callable to a placeholder with no default value', function () {

                    $this->parameter->hasDefaultValue->returns(false);

                    $test = $this->signature->bound($this->pool->get());

                    expect($test)->toEqual(new CallableWithPlaceholder(
                        $this->callable->get(),
                        'parameter'
                    ));

                });

            });

        });

    });

});
