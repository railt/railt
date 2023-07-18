<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use GraphQL\Type\Definition\InterfaceType;
use Railt\TypeSystem\InterfaceTypeDefinition;

/**
 * @template-extends ObjectLikeTypeBuilder<InterfaceTypeDefinition, InterfaceType>
 */
final class InterfaceTypeBuilder extends ObjectLikeTypeBuilder
{
    public function build(object $input): InterfaceType
    {
        assert($input instanceof InterfaceTypeDefinition, self::typeError(
            InterfaceTypeDefinition::class,
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
