<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Signatures\SignatureInterface;
use Quanta\DI\Signatures\SignatureWithParameter;
use Quanta\DI\Signatures\BindReflectionParameter;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

describe('BindReflectionParameter', function () {

    beforeEach(function () {

        $this->callable = new BindReflectionParameter;

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->signature = mock(SignatureInterface::class);
            $this->reflection = mock(ReflectionParameter::class);

        });

        context('when the given parameter is not variadic', function () {

            it('should return a new signature from the given signature and reflection parameter', function () {

                $this->reflection->isVariadic->returns(false);

                $test = ($this->callable)(
                    $this->signature->get(),
                    $this->reflection->get()
                );

                expect($test)->toEqual(new SignatureWithParameter(
                    $this->signature->get(),
                    new ReflectionParameterAdapter($this->reflection->get())
                ));

            });

        });

        context('when the given parameter is variadic', function () {

            it('should return the given signature', function () {

                $this->reflection->isVariadic->returns(true);

                $test = ($this->callable)(
                    $this->signature->get(),
                    $this->reflection->get()
                );

                expect($test)->toBe($this->signature->get());

            });

        });

    });

});
