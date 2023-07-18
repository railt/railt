<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use GraphQL\Type\Definition\EnumType;
use Railt\TypeSystem\EnumTypeDefinition;

/**
 * @template-extends Builder<EnumTypeDefinition, EnumType>
 */
final class EnumTypeBuilder extends Builder
{
    public function build(object $input): EnumType
    {
        assert($input instanceof EnumTypeDefinition, self::typeError(
            EnumTypeDefinition::class,
            $input,
        ));

        return new EnumType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'values' => $this->buildEnumValues($input),
        ]);
    }

    protected function buildEnumValues(EnumTypeDefinition $enum): array
    {
        $result = [];

        foreach ($enum->getValues() as $value) {
            $result[$value->getName()] = [
                'value' => $value->getValue(),
                'description' => $value->getDescription(),
            ];
        }

        return $result;
    }
}
