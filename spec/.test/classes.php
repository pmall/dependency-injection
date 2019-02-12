<?php

namespace Test;

final class TestClass
{
    public static function createStatic($a, string $b, SomeDependency $c, ?int $d, $e = 'e', ...$f)
    {
        //
    }

    public function create($a, string $b, SomeDependency $c, ?int $d, $e = 'e', ...$f)
    {
        //
    }

    public function __invoke($a, string $b, SomeDependency $c, ?int $d, $e = 'e', ...$f)
    {
        //
    }
}

final class TestClassWithConstructor
{
    public function __construct($a, string $b, SomeDependency $c, ?int $d, $e = 'e', ...$f)
    {
        //
    }
}

final class TestClassWithoutConstructor
{
    //
}
