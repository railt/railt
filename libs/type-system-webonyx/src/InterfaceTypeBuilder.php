<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\InterfaceType;
use Railt\TypeSystem\Definition\Type\InterfaceType;

/**
 * @template-extends ObjectLikeTypeBuilder<InterfaceType, InterfaceType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class InterfaceTypeBuilder extends ObjectLikeTypeBuilder
{
    public function build(object $input): InterfaceType
    {
        assert($input instanceof InterfaceType, self::typeError(
            InterfaceType::class,
            $input,
        ));

        return new InterfaceType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
            'interfaces' => $this->buildInterfaces($input),
        ]);
    }
}
