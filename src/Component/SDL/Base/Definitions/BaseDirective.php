<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Definitions;

use Railt\Component\SDL\Base\Dependent\Argument\BaseArgumentsContainer;
use Railt\Component\SDL\Contracts\Definitions;
use Railt\Component\SDL\Contracts\Definitions\Definition;
use Railt\Component\SDL\Contracts\Definitions\Directive\Location;
use Railt\Component\SDL\Contracts\Definitions\DirectiveDefinition;
use Railt\Component\SDL\Contracts\Dependent;
use Railt\Component\SDL\Contracts\Document;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseDirectiveDefinition
 */
abstract class BaseDirective extends BaseTypeDefinition implements DirectiveDefinition
{
    use BaseArgumentsContainer;

    /**
     * Type name
     */
    protected const TYPE_NAME = Type::DIRECTIVE;

    /**
     * Directive location type name
     */
    protected const LOCATION_TYPE_NAME = 'DirectiveLocation';

    /**
     * Mappings location to allowed type
     */
    protected const LOCATION_TARGET_MAPPINGS = [
        Location::TARGET_SCHEMA       => Definitions\SchemaDefinition::class,
        Location::TARGET_INPUT_OBJECT => Definitions\InputDefinition::class,

        Location::TARGET_OBJECT     => Definitions\ObjectDefinition::class,
        Location::TARGET_INTERFACE  => Definitions\InterfaceDefinition::class,
        Location::TARGET_UNION      => Definitions\UnionDefinition::class,
        Location::TARGET_SCALAR     => Definitions\ScalarDefinition::class,
        Location::TARGET_ENUM       => Definitions\EnumDefinition::class,
        Location::TARGET_ENUM_VALUE => Definitions\Enum\ValueDefinition::class,

        Location::TARGET_FIELD_DEFINITION    => Dependent\FieldDefinition::class,
        Location::TARGET_ARGUMENT_DEFINITION => Dependent\ArgumentDefinition::class,

        Location::TARGET_INPUT_FIELD_DEFINITION => Dependent\ArgumentDefinition::class,

        // Custom
        Location::TARGET_DOCUMENT => Document::class,
    ];

    /**
     * @var array|string[]
     */
    protected $locations = [];

    /**
     * @var array|null
     */
    private $allLocations;

    /**
     * @return iterable|string[]
     */
    public function getLocations(): iterable
    {
        return \array_values($this->locations);
    }

    /**
     * @return int
     */
    public function getNumberOfLocations(): int
    {
        return \count($this->locations);
    }

    /**
     * @param null|Definition $type
     * @return bool
     */
    public function isAllowedFor(?Definition $type): bool
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
                if ($type instanceof Dependent\ArgumentDefinition && ! $this->allowedForArgument($type)) {
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

        return \in_array($name, $this->locations, true);
    }

    /**
     * @param Dependent\ArgumentDefinition $type
     * @return bool
     */
    private function allowedForArgument(Dependent\ArgumentDefinition $type): bool
    {
        $location = $type->getParent() instanceof Definitions\InputDefinition
            ? Location::TARGET_INPUT_FIELD_DEFINITION
            : Location::TARGET_ARGUMENT_DEFINITION;

        return $this->hasLocation($location);
    }

    /**
     * @return bool
     */
    public function isAllowedForQueries(): bool
    {
        foreach ($this->locations as $location) {
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
        foreach ($this->locations as $location) {
            if (\in_array($location, Location::TARGET_GRAPHQL_SDL, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'locations',
            'arguments',
        ]);
    }

    /**
     * @return array
     */
    protected function getAllAllowedLocations(): array
    {
        if ($this->allLocations === null) {
            $locations = new \ReflectionClass(Location::class);

            $this->allLocations = \array_filter(\array_values($locations->getConstants()), '\\is_string');
        }

        return $this->allLocations;
    }
}
