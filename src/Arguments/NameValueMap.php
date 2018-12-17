<?php declare(strict_types=1);

namespace Quanta\DI\Arguments;

use Quanta\DI\Parameters\ParameterInterface;

final class NameValueMap implements ArgumentPoolInterface
{
    /**
     * The parameter name to value map.
     *
     * @var array
     */
    private $values;

    /**
     * Constructor.
     *
     * @param array values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    public function arguments(ParameterInterface $parameter): array
    {
        $name = $parameter->name();

        if (! key_exists($name, $this->values)) {
            return [];
        }

        $value = $this->values[$name];

        if (! $parameter->isVariadic()) {
            return [$value];
        }

        if (is_array($value)) {
            return $value;
        }

        throw new \LogicException(
            (string) new VariadicErrorMessage($parameter, $value)
        );
    }
}
