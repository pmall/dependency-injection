<?php declare(strict_types=1);

namespace Quanta\DI\Arguments\Pools;

use Psr\Container\ContainerInterface;

use Quanta\DI\Arguments\ArgumentInterface;
use Quanta\DI\Parameters\ParameterInterface;

final class DefaultArgumentPool extends AbstractArgumentPoolDecorator
{
    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $aliases = array_filter($options, [$this, 'isAlias']);

        $aliases = array_map([$this, 'cleanUpAlias'], $aliases);
        $values = array_diff_key($options, $aliases);

        parent::__construct(new CompositeArgumentPool(...[
            new NameValueMap(array_filter($values, [$this, 'isParameterName'], ARRAY_FILTER_USE_KEY)),
            new NameAliasMap(array_filter($aliases, [$this, 'isParameterName'], ARRAY_FILTER_USE_KEY)),
            new TypeHintValueMap(array_filter($values, [$this, 'isNotParameterName'], ARRAY_FILTER_USE_KEY)),
            new TypeHintAliasMap(array_filter($aliases, [$this, 'isNotParameterName'], ARRAY_FILTER_USE_KEY)),
            new FallbackArgumentPool,
        ]));
    }

    /**
     * Return whether the given value is an alias.
     *
     * @param mixed $value
     * @return bool
     */
    private function isAlias($value): bool
    {
        return is_string($value) && substr($value, 0, 1) == '@';
    }

    /**
     * Clean up the given alias string.
     *
     * @param string $alias
     * @return string
     */
    private function cleanUpAlias(string $alias): string
    {
        return substr($alias, 1);
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
