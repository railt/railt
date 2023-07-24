<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx\Builder;

use GraphQL\Type\Definition\FieldDefinition as WebonyxFieldDefinition;
use GraphQL\Type\Definition\Type;
use Railt\Bridge\Webonyx\Builder\Builder\Builder;
use Railt\TypeSystem\Definition\FieldDefinition;

/**
 * @template-extends Builder<FieldDefinition, WebonyxFieldDefinition>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class FieldBuilder extends Builder
{
    public function build(object $input): WebonyxFieldDefinition
    {
        assert($input instanceof FieldDefinition, self::typeError(
            FieldDefinition::class,
            $input,
        ));

        return WebonyxFieldDefinition::create([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'type' => function () use ($input): Type {
                return $this->type($input->getType());
            },
            'args' => $this->buildArguments($input),
            'deprecationReason' => $input->getDeprecationReason(),
        ]);
    }

    private function buildArguments(FieldDefinition $field): array
    {
        $builder = new FieldArgumentBuilder($this->builder);
        $result = [];

        foreach ($field->getArguments() as $argument) {
            $result[$argument->getName()] = $builder->build($argument);
        }

        return $result;
    }
}
