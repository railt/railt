<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Webonyx;

use GraphQL\Type\Definition\UnionType;
use Railt\TypeSystem\UnionTypeDefinition;

/**
 * @template-extends Builder<UnionTypeDefinition, UnionType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class UnionTypeBuilder extends Builder
{
    public function build(object $input): UnionType
    {
        assert($input instanceof UnionTypeDefinition, self::typeError(
            UnionTypeDefinition::class,
            $input,
        ));

        return new UnionType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'types' => $this->buildObjectTypes($input),
        ]);
    }

    protected function buildObjectTypes(UnionTypeDefinition $union): array
    {
        $result = [];

        foreach ($union->getTypes() as $type) {
            $result[] = $this->builder->getType($type);
        }

        return $result;
    }
}
