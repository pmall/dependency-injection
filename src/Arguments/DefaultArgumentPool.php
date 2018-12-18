<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Psr\Container\ContainerInterface;

final class DefaultArgumentPool extends AbstractArgumentPoolDecorator
{
    /**
     * Constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param array                             $options
     */
    public function __construct(ContainerInterface $container, array $options = [])
    {
        $aliases = array_filter($options, [$this, 'isAlias']);

        $aliases = array_map([$this, 'cleanUpAlias'], $aliases);
        $values = array_diff_key($options, $aliases);

        $nameValueMap = array_filter($values, [$this, 'isParameterName'], ARRAY_FILTER_USE_KEY);
        $nameAliasMap = array_filter($aliases, [$this, 'isParameterName'], ARRAY_FILTER_USE_KEY);
        $typeHintValueMap = array_filter($values, [$this, 'isNotParameterName'], ARRAY_FILTER_USE_KEY);
        $typeHintAliasMap = array_filter($aliases, [$this, 'isNotParameterName'], ARRAY_FILTER_USE_KEY);

        parent::__construct(new CompositeArgumentPool(...[
            new NameValueMap($nameValueMap),
            new NameAliasMap($container, $nameAliasMap),
            new TypeHintInstanceMap($typeHintValueMap),
            new TypeHintAliasMap($container, $typeHintAliasMap),
            new ContainerEntries($container),
            new NullValue,
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
