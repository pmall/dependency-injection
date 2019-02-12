<?php

use Quanta\DI\Parameters\Parameter;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\TypeHintErrorMessage;
use Quanta\DI\Parameters\DefaultValueErrorMessage;

describe('Parameter', function () {

    beforeEach(function () {

        $this->parameter = new Parameter('name');

    });

    it('should implement ParameterInterface', function () {

        expect($this->parameter)->toBeAnInstanceOf(ParameterInterface::class);

    });

    describe('->name()', function () {

        it('should return the parameter name', function () {

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

        it('should return false', function () {

            $test = $this->parameter->hasDefaultValue();

            expect($test)->toBeFalsy();

        });

    });

    describe('->defaultValue()', function () {

        it('should throw a LogicException', function () {

            expect([$this->parameter, 'defaultValue'])->toThrow(new LogicException(
                (string) new DefaultValueErrorMessage($this->parameter)
            ));

        });

    });

});
