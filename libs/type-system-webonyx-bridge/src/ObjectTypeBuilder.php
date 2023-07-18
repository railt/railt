<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use GraphQL\Type\Definition\ObjectType;
use Railt\TypeSystem\ObjectTypeDefinition;

/**
 * @template-extends ObjectLikeTypeBuilder<ObjectTypeDefinition, ObjectType>
 */
final class ObjectTypeBuilder extends ObjectLikeTypeBuilder
{
    public function build(object $input): ObjectType
    {
        assert($input instanceof ObjectTypeDefinition, self::typeError(
            ObjectTypeDefinition::class,
            $input,
        ));

        return new ObjectType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
            'interfaces' => $this->buildInterfaces($input),
        ]);
    }
}
