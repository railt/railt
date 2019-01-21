<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Railt\SDL\Contracts\Definitions\ScalarDefinition;

/**
 * Class ScalarBuilder
 * @property ScalarDefinition $reflection
 */
class ScalarBuilder extends Builder
{
    /**
     * @return ScalarType
     */
    public function build(): ScalarType
    {
        return $this->builtin($this->reflection, function (ScalarDefinition $reflection) {
            return $this->create($reflection);
        });
    }

    /**
     * @param ScalarDefinition $reflection
     * @param \Closure $otherwise
     * @return ScalarType
     */
    private function builtin(ScalarDefinition $reflection, \Closure $otherwise): ScalarType
    {
        switch ($reflection->getName()) {
            case 'String':
                return Type::string();

            case 'ID':
                return Type::id();

            case 'Boolean':
                return Type::boolean();

            case 'Int':
                return Type::int();

            case 'Float':
                return Type::float();
        }

        return $otherwise($reflection);
    }

    /**
     * @param ScalarDefinition $reflection
     * @return ScalarType
     * @throws InvariantViolation
     */
    private function create(ScalarDefinition $reflection): ScalarType
    {
        return new CustomScalarType([
            'name'         => $reflection->getName(),
            'description'  => $reflection->getDescription(),
            'serialize'    => function ($value) {
                return $this->serialize($value);
            },
            'parseValue'   => function ($value) {
                return $this->parse($value);
            },
            'parseLiteral' => function ($value) {
                return $this->parse($value);
            },
        ]);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function serialize($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function parse($value)
    {
        if (\is_object($value) && \property_exists($value, 'value')) {
            return $value->value;
        }

        return $value;
    }
}
