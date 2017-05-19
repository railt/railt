<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Support;

use Serafim\Railgun\Types\TypesRegistry;
use Serafim\Railgun\Types\Definitions\TypeDefinition;
use Serafim\Railgun\Types\Definitions\FieldDefinition;

/**
 * Trait InteractWithFields
 * @package Serafim\Railgun\Types\Support
 */
trait InteractWithFields
{
    /**
     * @param string $type
     * @return FieldDefinition
     */
    public function field(string $type): FieldDefinition
    {
        return new FieldDefinition($type);
    }

    /**
     * @param string $type
     * @return FieldDefinition|TypeDefinition
     */
    public function hasMany(string $type): FieldDefinition
    {
        return $this->field($type)->many();
    }

    /**
     * @return FieldDefinition
     */
    public function id(): FieldDefinition
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_ID);
    }

    /**
     * @return FieldDefinition
     */
    public function ids(): FieldDefinition
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_ID);
    }

    /**
     * @return FieldDefinition
     */
    public function integer(): FieldDefinition
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_INT);
    }

    /**
     * @return FieldDefinition
     */
    public function integers(): FieldDefinition
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_INT);
    }

    /**
     * @return FieldDefinition
     */
    public function string(): FieldDefinition
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return FieldDefinition
     */
    public function strings(): FieldDefinition
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return FieldDefinition
     */
    public function boolean(): FieldDefinition
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return FieldDefinition
     */
    public function booleans(): FieldDefinition
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return FieldDefinition
     */
    public function float(): FieldDefinition
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_FLOAT);
    }

    /**
     * @return FieldDefinition
     */
    public function floats(): FieldDefinition
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_FLOAT);
    }
}
