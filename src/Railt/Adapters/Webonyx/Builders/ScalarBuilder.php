<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Type;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Standard\Scalars\BooleanType;
use Railt\Reflection\Standard\Scalars\FloatType;
use Railt\Reflection\Standard\Scalars\IDType;
use Railt\Reflection\Standard\Scalars\IntType;
use Railt\Reflection\Standard\Scalars\StringType;

/**
 * @property-read ScalarDefinition $reflection
 */
class ScalarBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function build(): Type
    {
        $class = \get_class($this->reflection);

        switch ($class) {
            case StringType::class:
                return Type::string();

            case IDType::class:
                return Type::id();

            case BooleanType::class:
                return Type::boolean();

            case IntType::class:
                return Type::int();

            case FloatType::class:
                return Type::float();
        }

        $error = 'Can not build scalar type %s.';
        throw new \InvalidArgumentException(\sprintf($error, $this->reflection));
    }
}
