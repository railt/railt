<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Containers\BaseArgumentsContainer;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Contracts\Types\Directive\Location;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Contracts\Types\Enum\Value;
use Railt\Reflection\Contracts\Types\EnumType;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Types\InputType;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\ScalarType;
use Railt\Reflection\Contracts\Types\SchemaType;
use Railt\Reflection\Contracts\Types\TypeDefinition;
use Railt\Reflection\Contracts\Types\UnionType;

/**
 * Class BaseDirective
 */
abstract class BaseDirective extends BaseNamedType implements DirectiveType
{
    use BaseArgumentsContainer;

    /**
     * Mappings location to allowed type
     */
    protected const LOCATION_TARGET_MAPPINGS = [
        Location::TARGET_SCHEMA                 => SchemaType::class,
        Location::TARGET_OBJECT                 => ObjectType::class,
        Location::TARGET_INPUT_OBJECT           => InputType::class,
        Location::TARGET_INPUT_FIELD_DEFINITION => ArgumentType::class,
        Location::TARGET_ENUM                   => EnumType::class,
        Location::TARGET_ENUM_VALUE             => Value::class,
        Location::TARGET_UNION                  => UnionType::class,
        Location::TARGET_INTERFACE              => InterfaceType::class,
        Location::TARGET_FIELD_DEFINITION       => FieldType::class,
        Location::TARGET_ARGUMENT_DEFINITION    => ArgumentType::class,
        Location::TARGET_SCALAR                 => ScalarType::class,
    ];

    /**
     * @var array|string[]
     */
    protected $locations = [];

    /**
     * @return iterable|string[]
     */
    public function getLocations(): iterable
    {
        return \array_values($this->resolve()->locations);
    }

    /**
     * @param null|TypeDefinition $type
     * @return bool
     */
    public function isAllowedFor(?TypeDefinition $type): bool
    {
        if ($type === null) {
            return false;
        }

        foreach (self::LOCATION_TARGET_MAPPINGS as $out => $allowedType) {
            if ($type instanceof $allowedType && $this->hasLocation($out)) {
                //
                // If type is ArgumentType but:
                //
                // 1) Position: InputType > ArgumentType
                //      + Directive Location: ARGUMENT_DEFINITION
                //
                // 2) Position: FieldType > ArgumentType
                //      + Directive Location: INPUT_FIELD_DEFINITION
                //
                if ($type instanceof ArgumentType && ! $this->allowedForArgument($type)) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasLocation(string $name): bool
    {
        $name = \strtoupper($name);

        return \in_array($name, $this->resolve()->locations, true);
    }

    /**
     * @param ArgumentType $type
     * @return bool
     */
    private function allowedForArgument(ArgumentType $type): bool
    {
        $location = $type->getParent() instanceof InputType
            ? Location::TARGET_INPUT_FIELD_DEFINITION
            : Location::TARGET_ARGUMENT_DEFINITION;

        return $this->hasLocation($location);
    }

    /**
     * @return bool
     */
    public function isAllowedForQueries(): bool
    {
        foreach ($this->resolve()->locations as $location) {
            if (\in_array($location, Location::TARGET_GRAPHQL_QUERY, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isAllowedForSchemaDefinitions(): bool
    {
        foreach ($this->resolve()->locations as $location) {
            if (\in_array($location, Location::TARGET_GRAPHQL_SDL, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Directive';
    }

    /**
     * {@inheritdoc}
     * @internal Not allowed
     * @throws \BadMethodCallException
     */
    public function getDirectives(): iterable
    {
        throw new \BadMethodCallException(__METHOD__ . ' not allowed for directive type');
    }

    /**
     * {@inheritdoc}
     * @internal Not allowed
     * @throws \BadMethodCallException
     */
    public function hasDirective(string $name): bool
    {
        throw new \BadMethodCallException(__METHOD__ . ' not allowed for directive type');
    }

    /**
     * {@inheritdoc}
     * @internal Not allowed
     * @throws \BadMethodCallException
     */
    public function getDirective(string $name): ?DirectiveInvocation
    {
        throw new \BadMethodCallException(__METHOD__ . ' not allowed for directive type');
    }

    /**
     * {@inheritdoc}
     * @internal Not allowed
     * @throws \BadMethodCallException
     */
    public function getNumberOfDirectives(): int
    {
        throw new \BadMethodCallException(__METHOD__ . ' not allowed for directive type');
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'locations',
            'arguments'
        ]);
    }
}
