<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\UnionType;
use Railt\TypeSystem\Definition\Type\UnionType;

/**
 * @template-extends Builder<UnionType, UnionType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class UnionTypeBuilder extends Builder
{
    public function build(object $input): UnionType
    {
        assert($input instanceof UnionType, self::typeError(
            UnionType::class,
            $input,
        ));

        return new UnionType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'types' => $this->buildObjectTypes($input),
        ]);
    }

    protected function buildObjectTypes(UnionType $union): array
    {
        $result = [];

        foreach ($union->getTypes() as $type) {
            $result[] = $this->builder->getType($type);
        }

        return $result;
    }
}
