<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;

use Quanta\DI\Arguments\ArgumentPoolInterface;

use Quanta\DI\Signatures\CallableAdapter;
use Quanta\DI\Signatures\SignatureInterface;

describe('CallableAdapter', function () {

    beforeEach(function () {

        $this->callable = mock(CallableInterface::class);

        $this->signature = new CallableAdapter($this->callable->get());

    });

    it('should implement SignatureInterface', function () {

        expect($this->signature)->toBeAnInstanceOf(SignatureInterface::class);

    });

    describe('->bound()', function () {

        it('should return the callable', function () {

            $pool = mock(ArgumentPoolInterface::class);

            $test = $this->signature->bound($pool->get());

            expect($test)->toBe($this->callable->get());

        });

    });

});
