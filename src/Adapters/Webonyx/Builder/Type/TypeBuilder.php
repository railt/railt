<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builder\Type;

use GraphQL\Type\Definition\Type;
use Railt\Adapters\Webonyx\Builder\Builder;

/**
 * Class TypeBuilder
 * @package Railt\Adapters\Webonyx
 */
class TypeBuilder extends Builder
{
    /**
     * @return Type
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \LogicException
     */
    public function build(): Type
    {
        $type = $this->resolveRelationType();

        if ($this->type->isList()) {
            if ($this->type->getChild()->nonNull()) {
                $type = Type::nonNull($type);
            }

            $type = Type::listOf($type);
        }

        if ($this->type->nonNull()) {
            $type = Type::nonNull($type);
        }

        return $type;
    }

    /**
     * @return Type
     * @throws \LogicException
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     */
    private function resolveRelationType(): Type
    {
        $definition = $this->type->getRelationDefinition();

        return $this->resolve($definition->getName());
    }

}
