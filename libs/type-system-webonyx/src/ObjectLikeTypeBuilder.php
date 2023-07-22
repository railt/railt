<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\TypeWithFields;
use Railt\TypeSystem\Definition\Type\ObjectLikeTypeDefinition;

/**
 * @template TInput of ObjectLikeTypeDefinition
 * @template TOutput of TypeWithFields
 *
 * @template-extends Builder<TInput, TOutput>
 */
abstract class ObjectLikeTypeBuilder extends Builder
{
    protected function buildInterfaces(ObjectLikeTypeDefinition $object): array
    {
        $result = [];

        foreach ($object->getInterfaces() as $interface) {
            $result[] = $this->builder->getType($interface);
        }

        return $result;
    }

    protected function buildFields(ObjectLikeTypeDefinition $object): array
    {
        $builder = new FieldBuilder($this->builder);
        $result = [];

        foreach ($object->getFields() as $field) {
            $result[] = $builder->build($field);
        }

        return $result;
    }
}
