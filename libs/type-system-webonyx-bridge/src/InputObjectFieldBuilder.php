<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use GraphQL\Type\Definition\Type;
use Railt\TypeSystem\InputFieldDefinition;

/**
 * @template-extends Builder<InputFieldDefinition, array>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class InputObjectFieldBuilder extends Builder
{
    public function build(object $input): array
    {
        assert($input instanceof InputFieldDefinition, self::typeError(
            InputFieldDefinition::class,
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