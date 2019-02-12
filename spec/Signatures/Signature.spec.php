<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Signatures\Signature;
use Quanta\DI\Signatures\SignatureInterface;
use Quanta\DI\Signatures\ParameterSequenceInterface;

describe('Signature', function () {

    beforeEach(function () {

        $this->callable = mock(CallableInterface::class);
        $this->sequence = mock(ParameterSequenceInterface::class);

        $this->signature = new Signature(
            $this->callable->get(),
            $this->sequence->get()
        );

    });

    it('should implement SignatureInterface', function () {

        expect($this->signature)->toBeAnInstanceOf(SignatureInterface::class);

    });

    describe('->autowired()', function () {

        it('should return a callable from the given argument pool', function () {

            $pool = mock(ArgumentPoolInterface::class);
            $signature = mock(SignatureInterface::class);
            $callable = mock(CallableInterface::class);

            $this->sequence->signature->with($this->callable)->returns($signature);

            $signature->bound->with($pool)->returns($callable);

            $test = $this->signature->bound($pool->get());

            expect($test)->toBe($callable->get());

        });

    });

});
