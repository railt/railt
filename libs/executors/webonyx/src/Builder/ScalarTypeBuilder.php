<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\ScalarType as WebonyxScalarType;
use Railt\Executor\Webonyx\Builder\Builder;
use Railt\TypeSystem\Definition\Type\ScalarType;

/**
 * @template-extends Builder<ScalarType, WebonyxScalarType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class ScalarTypeBuilder extends Builder
{
    public function build(object $input): WebonyxScalarType
    {
        assert($input instanceof ScalarType, self::typeError(
            ScalarType::class,
            $input,
        ));

        $scalar =  match ($input->getName()) {
            'ID' => WebonyxScalarType::id(),
            'String' => WebonyxScalarType::string(),
            'Int' => WebonyxScalarType::int(),
            'Boolean' => WebonyxScalarType::boolean(),
            'Float' => WebonyxScalarType::float(),
            default => $this->create($input),
        };

        $scalar->description = $input->getDescription();

        return $scalar;
    }

    private function create(ScalarType $scalar): WebonyxScalarType
    {
        return new class ($scalar) extends CustomScalarType {
            public function __construct(ScalarType $scalar)
            {
                parent::__construct([
                    'name' => $scalar->getName(),
                    'description' => $scalar->getDescription(),
                    'specifiedBy' => $scalar->getSpecificationUrl(),
                ]);
            }

            public function serialize($value): mixed
            {
                return $value;
            }

            public function parseValue($value): mixed
            {
                return $value;
            }
        };
    }
}
