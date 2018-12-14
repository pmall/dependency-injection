<?php declare(strict_types=1);

namespace Quanta\DI;

use Quanta\DI\Parameters\ParameterInterface;
use Quanta\DI\Parameters\ReflectionParameterAdapter;

final class Instantiation
{
    /**
     * The class name.
     *
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * Return an instance of the class with the given argument.
     *
     * @param mixed ...$xs
     */
    public function __invoke(...$xs)
    {
        return new $this->class(...$xs);
    }
}
