<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ScalarType;
use Railt\Reflection\Abstraction\ScalarTypeInterface;

/**
 * Class ScalarTypeBuilder
 * @package Railt\Adapters\Webonyx\Builder
 * @property-read ScalarTypeInterface $type
 */
class ScalarTypeBuilder extends Builder
{
    /**
     * @return ScalarType
     * @throws \LogicException
     */
    public function build(): ScalarType
    {
        $name = $this->type->getName();

        switch ($name) {
            case 'ID':
                return Type::id();
            case 'Int':
                return Type::int();
            case 'String':
                return Type::string();
            case 'Float':
                return Type::float();
            case 'Boolean':
                return Type::boolean();
        }

        throw new \LogicException('Type ' . $name . ' does not supported yet');
    }
}
