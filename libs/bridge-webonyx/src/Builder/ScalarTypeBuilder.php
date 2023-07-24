<?php

declare(strict_types=1);

namespace Railt\Bridge\Webonyx\Builder;

use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\ScalarType as WebonyxScalarType;
use Railt\Bridge\Webonyx\Builder\Builder\Builder;
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

        return match ($input->getName()) {
            'ID' => WebonyxScalarType::id(),
            'String' => WebonyxScalarType::string(),
            'Int' => WebonyxScalarType::int(),
            'Boolean' => WebonyxScalarType::boolean(),
            'Float' => WebonyxScalarType::float(),
            default => $this->create($input),
        };
    }

    private function create(ScalarType $scalar): WebonyxScalarType
    {
        return new class($scalar) extends CustomScalarType {
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
