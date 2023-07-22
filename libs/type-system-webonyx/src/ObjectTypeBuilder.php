<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\ObjectType;
use Railt\TypeSystem\Definition\Type\ObjectType;

/**
 * @template-extends ObjectLikeTypeBuilder<ObjectType, ObjectType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class ObjectTypeBuilder extends ObjectLikeTypeBuilder
{
    public function build(object $input): ObjectType
    {
        assert($input instanceof ObjectType, self::typeError(
            ObjectType::class,
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
