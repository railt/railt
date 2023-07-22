<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\InputObjectType;
use Railt\TypeSystem\Definition\Type\InputObjectTypeDefinition;

/**
 * @template-extends Builder<InputObjectTypeDefinition, InputObjectType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class InputObjectTypeBuilder extends Builder
{
    public function build(object $input): InputObjectType
    {
        assert($input instanceof InputObjectTypeDefinition, self::typeError(
            InputObjectTypeDefinition::class,
            $input,
        ));

        return new InputObjectType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
        ]);
    }

    private function buildFields(InputObjectTypeDefinition $input): array
    {
        $builder = new InputObjectFieldBuilder($this->builder);
        $result = [];

        foreach ($input->getFields() as $field) {
            $result[$field->getName()] = $builder->build($field);
        }

        return $result;
    }
}
