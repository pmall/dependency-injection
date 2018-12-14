<?php

namespace Test;

final class TestClassWithoutConstructor
{
    //
}

final class TestClassWithoutParameter
{
    public function __construct()
    {
        //
    }
}

final class TestClass
{
    public function __construct($a = null, $b = null, $c = null)
    {
        //
    }

    public static function createStatic($a = null, $b = null, $c = null)
    {

    }

    public function create($a = null, $b = null, $c = null)
    {

    }

    public function __invoke($a = null, $b = null, $c = null)
    {
        //
    }
}
