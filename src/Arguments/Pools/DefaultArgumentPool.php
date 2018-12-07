<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;
use Quanta\Exceptions\ArrayTypeCheckTrait;
use Quanta\Exceptions\ArrayArgumentTypeErrorMessage;

final class DefaultArgumentPool extends AbstractArgumentPoolDecorator
{
    use ArrayTypeCheckTrait;

    /**
     * Constructor.
     *
     * @var string[]    $aliases
     * @var array       $values
     * @throws \InvalidArgumentException
     */
    public function __construct(array $aliases = [], array $values = [])
    {
        if (! $this->areAllTypedAs('string', $aliases)) {
            throw new \InvalidArgumentException(
                (string) new ArrayArgumentTypeErrorMessage(1, 'string', $aliases)
            );
        }

        parent::__construct(new CompositeArgumentPool(...[
            new NameValueMap(array_filter($values, [$this, 'isParameterName'], ARRAY_FILTER_USE_KEY)),
            new NameAliasMap(array_filter($aliases, [$this, 'isParameterName'], ARRAY_FILTER_USE_KEY)),
            new TypeHintValueMap(array_filter($values, [$this, 'isNotParameterName'], ARRAY_FILTER_USE_KEY)),
            new TypeHintAliasMap(array_filter($aliases, [$this, 'isNotParameterName'], ARRAY_FILTER_USE_KEY)),
            new ContainerEntries,
        ]));
    }

    /**
     * Return whether the given key is a parameter name (starts with a $).
     *
     * @param string $key
     * @return bool
     */
    private function isParameterName(string $key): bool
    {
        return substr($key, 0, 1) == '$';
    }

    /**
     * Return whether the given key is not a parameter name.
     *
     * @param string $key
     * @return bool
     */
    private function isNotParameterName(string $key): bool
    {
        return ! $this->isParameterName($key);
    }
}
