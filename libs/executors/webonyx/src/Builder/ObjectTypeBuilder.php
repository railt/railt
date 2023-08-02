<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\ObjectType as WebonyxObjectType;
use Railt\TypeSystem\Definition\Type\ObjectType;

/**
 * @template-extends ObjectLikeTypeBuilder<ObjectType, WebonyxObjectType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class ObjectTypeBuilder extends ObjectLikeTypeBuilder
{
    public function build(object $input): WebonyxObjectType
    {
        assert($input instanceof ObjectType, self::typeError(
            ObjectType::class,
            $input,
        ));

        return new WebonyxObjectType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
            'interfaces' => $this->buildInterfaces($input),
        ]);
    }
}
