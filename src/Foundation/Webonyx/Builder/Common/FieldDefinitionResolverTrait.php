<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder\Common;

use GraphQL\Type\Definition\ResolveInfo;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\Field\HasFields;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Trait FieldDefinitionResolverTrait
 */
trait FieldDefinitionResolverTrait
{
    /**
     * @param ResolveInfo $info
     * @return FieldDefinition
     */
    protected function getFieldDefinition(ResolveInfo $info): FieldDefinition
    {
        /** @var HasFields $reflection */
        $reflection = $this->loadReflection($info->parentType->name);

        \assert($reflection instanceof HasFields);

        return $reflection->getField($info->fieldName);
    }

    /**
     * @param string $name
     * @return TypeDefinition
     */
    abstract protected function loadReflection(string $name): TypeDefinition;
}
