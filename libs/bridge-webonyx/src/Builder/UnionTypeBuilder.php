<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx\Builder;

use GraphQL\Type\Definition\UnionType as WebonyxUnionType;
use Railt\Bridge\Webonyx\Builder\Builder\Builder;
use Railt\TypeSystem\Definition\Type\UnionType;

/**
 * @template-extends Builder<UnionType, WebonyxUnionType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class UnionTypeBuilder extends Builder
{
    public function build(object $input): WebonyxUnionType
    {
        assert($input instanceof UnionType, self::typeError(
            UnionType::class,
            $input,
        ));

        return new WebonyxUnionType([
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
