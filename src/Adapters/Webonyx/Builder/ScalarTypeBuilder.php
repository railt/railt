<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ScalarType;
use Railgun\Reflection\Abstraction\ScalarTypeInterface;

/**
 * Class ScalarTypeBuilder
 * @package Railgun\Webonyx\Builder
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
