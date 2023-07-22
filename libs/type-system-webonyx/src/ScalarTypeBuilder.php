<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Statement\Webonyx;

use GraphQL\Type\Definition\CustomScalarType;
use GraphQL\Type\Definition\ScalarType;
use Railt\TypeSystem\Definition\Type\ScalarType;

/**
 * @template-extends Builder<ScalarType, ScalarType>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class ScalarTypeBuilder extends Builder
{
    public function build(object $input): ScalarType
    {
        assert($input instanceof ScalarType, self::typeError(
            ScalarType::class,
            $input,
        ));

        return match ($input->getName()) {
            'ID' => ScalarType::id(),
            'String' => ScalarType::string(),
            'Int' => ScalarType::int(),
            'Boolean' => ScalarType::boolean(),
            'Float' => ScalarType::float(),
            default => $this->create($input),
        };
    }

    private function create(ScalarType $scalar): ScalarType
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
