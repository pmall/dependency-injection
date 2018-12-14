<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ParameterCollection;
use Quanta\DI\Parameters\ParameterCollectionInterface;

describe('ParameterCollection', function () {

    beforeEach(function () {

        $this->parameter1 = mock(ParameterInterface::class);
        $this->parameter2 = mock(ParameterInterface::class);
        $this->parameter3 = mock(ParameterInterface::class);

        $this->collection = new ParameterCollection(...[
            $this->parameter1->get(),
            $this->parameter2->get(),
            $this->parameter3->get(),
        ]);

    });

    it('should implement ParameterCollectionInterface', function () {

        expect($this->collection)->toBeAnInstanceOf(ParameterCollectionInterface::class);

    });

    describe('->parameters()', function () {

        it('should return the array of parameters', function () {

            $test = $this->collection->parameters();

            expect($test)->toBeAn('array');
            expect($test)->toHaveLength(3);
            expect($test[0])->toBe($this->parameter1->get());
            expect($test[1])->toBe($this->parameter2->get());
            expect($test[2])->toBe($this->parameter3->get());

        });

    });

});
