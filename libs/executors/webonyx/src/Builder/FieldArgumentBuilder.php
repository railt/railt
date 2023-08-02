<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use Railt\Executor\Webonyx\Builder\Builder;
use Railt\TypeSystem\Definition\ArgumentDefinition;

/**
 * @template-extends Builder<ArgumentDefinition, array>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class FieldArgumentBuilder extends Builder
{
    public function build(object $input): array
    {
        assert($input instanceof ArgumentDefinition, self::typeError(
            ArgumentDefinition::class,
            $input,
        ));

        $result = [
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'type' => function () use ($input): Type {
                return $this->type($input->getType());
            },
            'deprecationReason' => $input->getDeprecationReason(),
        ];

        if ($input->hasDefaultValue()) {
            /** @psalm-suppress MixedAssignment : Okay */
            $result['defaultValue'] = $this->value($input->getDefaultValue());
        }

        return $result;
    }
}
