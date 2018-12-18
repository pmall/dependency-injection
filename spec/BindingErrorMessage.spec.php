<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\DI\BindingErrorMessage;
use Quanta\DI\Parameters\ParameterInterface;

describe('BindingErrorMessage', function () {

    context('when there is no parameter', function () {

        it('should throw an ArgumentCountError', function () {

            $test = function () { new BindingErrorMessage('some callable'); };

            expect($test)->toThrow(new ArgumentCountError);

        });

    });

    context('when there is at least one parameter', function () {

        describe('->__toString()', function () {

            context('when there is one parameter', function () {

                it('should return a singular message', function () {

                    $parameter = mock(ParameterInterface::class);

                    $parameter->name->returns('$name');

                    $test = (string) new BindingErrorMessage('some callable', $parameter->get());

                    expect($test)->toBeA('string');
                    expect($test)->toContain('some callable');
                    expect($test)->toContain('parameter $name');

                });

            });

            context('when there is more than one parameter', function () {

                it('should return a plural message', function () {

                    $parameter1 = mock(ParameterInterface::class);
                    $parameter2 = mock(ParameterInterface::class);
                    $parameter3 = mock(ParameterInterface::class);

                    $parameter1->name->returns('$name1');
                    $parameter2->name->returns('$name2');
                    $parameter3->name->returns('$name3');

                    $test = (string) new BindingErrorMessage('some callable', ...[
                        $parameter1->get(),
                        $parameter2->get(),
                        $parameter3->get(),
                    ]);

                    expect($test)->toBeA('string');
                    expect($test)->toContain('some callable');
                    expect($test)->toContain('parameters $name1, $name2 and $name3');

                });

            });

        });

    });

});
