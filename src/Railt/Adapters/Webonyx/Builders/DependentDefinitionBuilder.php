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
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;

/**
 * @property AllowsTypeIndication $reflection
 */
abstract class DependentDefinitionBuilder extends TypeBuilder
{
    /**
     * @return Type
     * @throws \InvalidArgumentException
     */
    protected function buildType(): Type
    {
        /** @var Type $result */
        $type = $this->load($this->reflection->getTypeDefinition());

        if ($this->reflection->isListOfNonNulls()) {
            $type = Type::listOf(Type::nonNull($type));
        } elseif ($this->reflection->isList()) {
            $type = Type::listOf($type);
        }

        if ($this->reflection->isNonNull()) {
            $type = Type::nonNull($type);
        }

        return $type;
    }
}
