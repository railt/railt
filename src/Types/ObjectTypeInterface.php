<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

use Serafim\Railgun\Schema\Fields;
use Serafim\Railgun\Schema\SchemaInterface;
use Serafim\Railgun\Schema\Creators\FieldDefinitionCreator;

/**
 * Interface ObjectTypeInterface
 * @package Serafim\Railgun\Types
 */
interface ObjectTypeInterface extends TypeInterface
{
    /**
     * @param Fields|SchemaInterface $fields
     * @return iterable|FieldDefinitionCreator[]
     */
    public function getFields(Fields $fields): iterable;

    /**
     * @return iterable|string[]
     */
    public function getInterfaces(): iterable;
}
