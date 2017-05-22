<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Schemas;

use Serafim\Railgun\Types\Creators\FieldCreator;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;

/**
 * Class Fields
 * @package Serafim\Railgun\Types\Schemas
 *
 * @method FieldTypeInterface|FieldCreator id()
 * @method FieldTypeInterface|FieldCreator ids()
 * @method FieldTypeInterface|FieldCreator integer()
 * @method FieldTypeInterface|FieldCreator integers()
 * @method FieldTypeInterface|FieldCreator string()
 * @method FieldTypeInterface|FieldCreator strings()
 * @method FieldTypeInterface|FieldCreator boolean()
 * @method FieldTypeInterface|FieldCreator booleans()
 * @method FieldTypeInterface|FieldCreator float()
 * @method FieldTypeInterface|FieldCreator floats()
 *
 */
class Fields extends AbstractSchema
{
    /**
     * Fields constructor.
     */
    final public function __construct()
    {
        parent::__construct(FieldCreator::class);
    }

    /**
     * @param string $type
     * @return FieldTypeInterface|FieldCreator
     */
    public function field(string $type): FieldTypeInterface
    {
        return parent::make($type);
    }

    /**
     * @param string $type
     * @return FieldTypeInterface|FieldCreator
     */
    public function hasMany(string $type): FieldTypeInterface
    {
        return parent::list($type);
    }
}
