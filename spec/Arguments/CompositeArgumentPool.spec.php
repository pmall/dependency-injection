<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\Arguments\ArgumentPoolInterface;
use Quanta\DI\Arguments\CompositeArgumentPool;

use Quanta\DI\Parameters\ParameterInterface;

describe('CompositeArgumentPool', function () {

    it('should implement ArgumentPoolInterface', function () {

        expect(new CompositeArgumentPool)->toBeAnInstanceOf(ArgumentPoolInterface::class);

    });

    describe('->arguments()', function () {

        beforeEach(function () {

            $this->parameter = mock(ParameterInterface::class);

        });

        context('when there is no argument pool', function () {

            it('should return an empty array', function () {

                $pool = new CompositeArgumentPool;

                $test = $pool->arguments($this->parameter->get());

                expect($test)->toBeAn('array');
                expect($test)->toHaveLength(0);

            });

        });

        context('when there is at least one argument pool', function () {

            beforeEach(function () {

                $this->pool1 = mock(ArgumentPoolInterface::class);
                $this->pool2 = mock(ArgumentPoolInterface::class);
                $this->pool3 = mock(ArgumentPoolInterface::class);

                $this->pool = new CompositeArgumentPool(...[
                    $this->pool1->get(),
                    $this->pool2->get(),
                    $this->pool3->get(),
                ]);

            });

            context('when all argument pools return an empty array', function () {

                it('should return an empty array', function () {

                    $this->pool1->arguments->with($this->parameter)->returns([]);
                    $this->pool2->arguments->with($this->parameter)->returns([]);
                    $this->pool3->arguments->with($this->parameter)->returns([]);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(0);

                });

            });

            context('when at least one argument pool does not return an empty array', function () {

                it('should return the first non empty array', function () {

                    $this->pool1->arguments->with($this->parameter)->returns([]);
                    $this->pool2->arguments->with($this->parameter)->returns(['value1']);
                    $this->pool3->arguments->with($this->parameter)->returns(['value2']);

                    $test = $this->pool->arguments($this->parameter->get());

                    expect($test)->toBeAn('array');
                    expect($test)->toHaveLength(1);
                    expect($test[0])->toEqual('value1');

                });

            });

        });

    });

});
