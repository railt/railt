<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\TypeWithFields;
use Railt\Executor\Webonyx\Builder\Builder;
use Railt\Executor\Webonyx\Builder\Internal\BuilderFactory;
use Railt\TypeSystem\Definition\Type\ObjectLikeType;

/**
 * @template TInput of ObjectLikeType
 * @template TOutput of TypeWithFields
 *
 * @template-extends Builder<TInput, TOutput>
 */
abstract class ObjectLikeTypeBuilder extends Builder
{
    private readonly FieldBuilder $fields;

    public function __construct(BuilderFactory $builder)
    {
        parent::__construct($builder);

        $this->fields = new FieldBuilder($builder);
    }

    protected function buildInterfaces(ObjectLikeType $object): array
    {
        $result = [];

        foreach ($object->getInterfaces() as $interface) {
            $result[] = $this->builder->getType($interface);
        }

        return $result;
    }

    protected function buildFields(ObjectLikeType $object): array
    {
        $result = [];

        foreach ($object->getFields() as $field) {
            $result[] = $this->fields->build($field);
        }

        return $result;
    }
}
