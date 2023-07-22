<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\Type;
use Railt\TypeSystem\Definition\FieldDefinition as SourceFieldDefinition;

/**
 * @template-extends Builder<SourceFieldDefinition, FieldDefinition>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class FieldBuilder extends Builder
{
    public function build(object $input): FieldDefinition
    {
        assert($input instanceof SourceFieldDefinition, self::typeError(
            SourceFieldDefinition::class,
            $input,
        ));

        return FieldDefinition::create([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'type' => function () use ($input): Type {
                return $this->type($input->getType());
            },
            'args' => $this->buildArguments($input),
            'deprecationReason' => $input->getDeprecationReason(),
        ]);
    }

    private function buildArguments(SourceFieldDefinition $field): array
    {
        $builder = new FieldArgumentBuilder($this->builder);
        $result = [];

        foreach ($field->getArguments() as $argument) {
            $result[$argument->getName()] = $builder->build($argument);
        }

        return $result;
    }
}
