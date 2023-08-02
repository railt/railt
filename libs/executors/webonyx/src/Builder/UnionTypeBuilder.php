<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\UnionType as WebonyxUnionType;
use Railt\Executor\Webonyx\Builder\Builder;
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
