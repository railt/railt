<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\FieldDefinition as WebonyxFieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Railt\Executor\Webonyx\Builder\Internal\BuilderFactory;
use Railt\Executor\Webonyx\Executor\Context;
use Railt\Executor\Webonyx\Http\WebonyxInput;
use Railt\Foundation\Event\Resolve\FieldResolved;
use Railt\Foundation\Event\Resolve\FieldResolving;
use Railt\TypeSystem\Definition\FieldDefinition;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\NonNullType;

/**
 * @template-extends Builder<FieldDefinition, WebonyxFieldDefinition>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class FieldBuilder extends Builder
{
    private readonly FieldArgumentBuilder $fieldArguments;

    public function __construct(BuilderFactory $builder)
    {
        parent::__construct($builder);

        $this->fieldArguments = new FieldArgumentBuilder($builder);
    }

    public function build(object $input): WebonyxFieldDefinition
    {
        assert($input instanceof FieldDefinition, self::typeError(
            FieldDefinition::class,
            $input,
        ));

        return WebonyxFieldDefinition::create([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'type' => $this->getTypeLoader($input),
            'resolve' => $this->getResolver($input),
            'args' => $this->buildArguments($input),
            'deprecationReason' => $input->getDeprecationReason(),
        ]);
    }

    private function getTypeLoader(FieldDefinition $field): \Closure
    {
        return function () use ($field): Type {
            return $this->type($field->getType());
        };
    }

    /**
     * @psalm-suppress MixedAssignment : Allow mixed types
     */
    private function getResolver(FieldDefinition $field): \Closure
    {
        return function (mixed $parent, array $args, Context $ctx, ResolveInfo $info) use ($field): mixed {
            /** @var array<non-empty-string, mixed> $args */
            $input = new WebonyxInput(
                request: $ctx->request,
                field: $field,
                info: $info,
                arguments: $args,
            );

            /** @var FieldResolving $resolving */
            $resolving = $ctx->dispatcher->dispatch(new FieldResolving($input));

            $result = null;
            try {
                if ($resolving->hasResult()) {
                    return $result = $resolving->getResult();
                }

                return $result = $this->getDefaultResultValue($field, $parent);
            } finally {
                $ctx->dispatcher->dispatch(new FieldResolved($resolving->input, $result));
            }
        };
    }

    private function getDefaultResultValue(FieldDefinition $field, mixed $parent): mixed
    {
        $type = $field->getType();

        if ($type instanceof ScalarType) {
            return null;
        }

        if ($type instanceof NonNullType && $type->getOfType() instanceof ScalarType) {
            return $parent;
        }

        return [];
    }

    private function buildArguments(FieldDefinition $field): array
    {
        $result = [];

        foreach ($field->getArguments() as $argument) {
            $result[$argument->getName()] = $this->fieldArguments->build($argument);
        }

        return $result;
    }
}
