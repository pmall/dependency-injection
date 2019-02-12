<?php declare(strict_types=1);

namespace Quanta\DI\Parameters;

final class TypeHint
{
    /**
     * The class name.
     *
     * @var string
     */
    private $class;

    /**
     * Whether the argument can be null.
     *
     * @var bool
     */
    private $nullable;

    /**
     * Constructor.
     *
     * @param string    $class
     * @param bool      $nullable
     */
    public function __construct(string $class, bool $nullable)
    {
        $this->class = $class;
        $this->nullable = $nullable;
    }

    /**
     * Return the class name.
     *
     * @return string
     */
    public function class(): string
    {
        return $this->class;
    }

    /**
     * Return whether the argument can be null.
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
