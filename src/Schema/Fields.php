<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema;

use Serafim\Railgun\Schema\Creators\CreatorInterface;
use Serafim\Railgun\Schema\Creators\FieldDefinitionCreator;

/**
 * Class Fields
 * @package Serafim\Railgun\Schema
 */
class Fields extends AbstractSchema
{
    /**
     * Arguments constructor.
     */
    public function __construct()
    {
        parent::__construct(FieldDefinitionCreator::class);
    }

    /**
     * @param string $type
     * @return CreatorInterface|FieldDefinitionCreator
     */
    public function hasMany(string $type): FieldDefinitionCreator
    {
        return $this->listOf($type);
    }

    /**
     * @param string $type
     * @return CreatorInterface|FieldDefinitionCreator
     */
    public function hasOne(string $type): FieldDefinitionCreator
    {
        return $this->typeOf($type);
    }
}
