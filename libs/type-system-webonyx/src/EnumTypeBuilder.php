<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\EnumType;
use Railt\TypeSystem\Definition\Type\EnumType;

/**
 * @template-extends Builder<EnumType, EnumType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class EnumTypeBuilder extends Builder
{
    public function build(object $input): EnumType
    {
        assert($input instanceof EnumType, self::typeError(
            EnumType::class,
            $input,
        ));

        return new EnumType([
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
