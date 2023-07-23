<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Webonyx;

use GraphQL\Type\Definition\EnumType as WebonyxEnumType;
use Railt\TypeSystem\Definition\Type\EnumType;

/**
 * @template-extends Builder<EnumType, WebonyxEnumType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class EnumTypeBuilder extends Builder
{
    public function build(object $input): WebonyxEnumType
    {
        assert($input instanceof EnumType, self::typeError(
            EnumType::class,
            $input,
        ));

        return new WebonyxEnumType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'values' => $this->buildEnumValues($input),
        ]);
    }

    protected function buildEnumValues(EnumType $enum): array
    {
        $result = [];

        foreach ($enum->getValues() as $value) {
            $result[$value->getName()] = [
                'value' => $value->getValue(),
                'description' => $value->getDescription(),
                'deprecationReason' => $value->getDeprecationReason(),
            ];
        }

        return $result;
    }
}
