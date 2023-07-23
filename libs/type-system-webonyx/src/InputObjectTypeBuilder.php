<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Webonyx;

use GraphQL\Type\Definition\InputObjectType as WebonyxInputObjectType;
use Railt\TypeSystem\Definition\Type\InputObjectType;

/**
 * @template-extends Builder<InputObjectType, WebonyxInputObjectType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class InputObjectTypeBuilder extends Builder
{
    public function build(object $input): WebonyxInputObjectType
    {
        assert($input instanceof InputObjectType, self::typeError(
            InputObjectType::class,
            $input,
        ));

        return new WebonyxInputObjectType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
        ]);
    }

    private function buildFields(InputObjectType $input): array
    {
        $builder = new InputObjectFieldBuilder($this->builder);
        $result = [];

        foreach ($input->getFields() as $field) {
            $result[$field->getName()] = $builder->build($field);
        }

        return $result;
    }
}
