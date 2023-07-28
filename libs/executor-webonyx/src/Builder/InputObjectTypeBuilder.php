<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\InputObjectType as WebonyxInputObjectType;
use Railt\Executor\Webonyx\Builder\Builder;
use Railt\Executor\Webonyx\Builder\Internal\BuilderFactory;
use Railt\TypeSystem\Definition\Type\InputObjectType;

/**
 * @template-extends Builder<InputObjectType, WebonyxInputObjectType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class InputObjectTypeBuilder extends Builder
{
    private readonly InputObjectFieldBuilder $inputFields;

    public function __construct(BuilderFactory $builder)
    {
        parent::__construct($builder);

        $this->inputFields = new InputObjectFieldBuilder($builder);
    }

    public function build(object $input): WebonyxInputObjectType
    {
        assert($input instanceof InputObjectType, self::typeError(
            InputObjectType::class,
            $input,
        ));

        return new WebonyxInputObjectType([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'fields' => $this->buildFields($input),
        ]);
    }

    private function buildFields(InputObjectType $input): array
    {
        $result = [];

        foreach ($input->getFields() as $field) {
            $result[$field->getName()] = $this->inputFields->build($field);
        }

        return $result;
    }
}
