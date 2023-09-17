<?php

declare(strict_types=1);

namespace Railt\SDL;

use Railt\SDL\Config\GenerateSchema;
use Railt\SDL\Config\Specification;
use Railt\TypeSystem\Definition\SchemaDefinition;

final class Config
{
    /**
     * @param Specification $spec The version of the GraphQL specification that
     *        is used as the basis for the compiler.
     * @param GenerateSchema|null $generateSchema Setting is responsible for
     *        automatically generating the schema ({@see SchemaDefinition}) if
     *        it is missing. In that case, the value is specified as {@see null},
     *        then the schema is not generated.
     * @param bool $castIntToFloat Allow Integers to be converted to
     *        Floats. Thus, any argument that requires a Float scalar type will
     *        also accept Integer scalars, which will be automatically converted
     *        to the correct format.
     * @param bool $castScalarToString Allow Scalars to be converted to
     *        Strings "as is". Thus, any argument that requires a String scalar
     *        type will also accept any scalars, which will be automatically
     *        converted to the correct string format.
     * @param bool $castNullableTypeToDefaultValue In the case that an argument
     *        is defined as a nullable type, then that argument may be omitted
     *        during use.
     *        ```
     *          directive @param bool $castListTypeToDefaultValue In the case that an argument is
     *        defined as a list type, then that argument may be omitted
     *        during use.
     *        ```
     *          directive @example(nullable: String) on FIELD_DEFINITION
     *
     *          type Example {
     *              # - Ok: Default value is NULL in case of this option is "true"
     *              # - Error: Like "The argument<nullable> of directive<@example> is required"
     *              #          in case of this option is "false"
     *              field: String! @example
     *          }
     *        ```
     * @example(list: [String!]!) on FIELD_DEFINITION
     *
     *          type Example {
     *              # - Ok: Default value is empty list "[]" in case of this
     *              #       option is "true"
     *              # - Error: Like "The argument<list> of directive<@example> is required"
     *              #          in case of this option is "false"
     *              field: String! @example
     *          }
     *        ```
     */
    public function __construct(
        public readonly Specification $spec = Specification::DEFAULT,
        public readonly ?GenerateSchema $generateSchema = new GenerateSchema(),
        public readonly bool $castIntToFloat = true,
        public readonly bool $castScalarToString = true,
        public readonly bool $castNullableTypeToDefaultValue = true,
        public readonly bool $castListTypeToDefaultValue = true,
    ) {}

    public static function strict(Specification $spec = Specification::DEFAULT): self
    {
        return new self(
            spec: $spec,
            castIntToFloat: false,
            castScalarToString: false,
            castNullableTypeToDefaultValue: false,
            castListTypeToDefaultValue: false,
        );
    }
}
