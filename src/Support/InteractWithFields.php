<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Support;

use Serafim\Railgun\Types\TypesRegistry;
use Serafim\Railgun\Types\Creators\FieldCreator;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;

/**
 * Trait InteractWithFields
 * @package Serafim\Railgun\Support
 */
trait InteractWithFields
{
    /**
     * @param string $type
     * @return FieldTypeInterface|FieldCreator
     */
    public function field(string $type): FieldTypeInterface
    {
        return new FieldCreator($type);
    }

    /**
     * @param string $type
     * @return FieldTypeInterface|FieldCreator
     */
    public function hasMany(string $type): FieldTypeInterface
    {
        return $this->field($type)->many();
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function id(): FieldTypeInterface
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_ID);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function ids(): FieldTypeInterface
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_ID);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function integer(): FieldTypeInterface
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_INT);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function integers(): FieldTypeInterface
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_INT);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function string(): FieldTypeInterface
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function strings(): FieldTypeInterface
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_STRING);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function boolean(): FieldTypeInterface
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function booleans(): FieldTypeInterface
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_BOOLEAN);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function float(): FieldTypeInterface
    {
        return $this->field(TypesRegistry::INTERNAL_TYPE_FLOAT);
    }

    /**
     * @return FieldTypeInterface|FieldCreator
     */
    public function floats(): FieldTypeInterface
    {
        return $this->hasMany(TypesRegistry::INTERNAL_TYPE_FLOAT);
    }
}
