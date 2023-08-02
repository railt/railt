<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\InterfaceType as WebonyxInterfaceType;
use Railt\TypeSystem\Definition\Type\InterfaceType;

/**
 * @template-extends ObjectLikeTypeBuilder<InterfaceType, WebonyxInterfaceType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class InterfaceTypeBuilder extends ObjectLikeTypeBuilder
{
    public function build(object $input): WebonyxInterfaceType
    {
        assert($input instanceof InterfaceType, self::typeError(
            InterfaceType::class,
            $input,
        ));

        return new WebonyxInterfaceType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
            'interfaces' => $this->buildInterfaces($input),
        ]);
    }
}
