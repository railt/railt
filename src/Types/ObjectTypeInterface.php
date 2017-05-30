<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

use Serafim\Railgun\Schema\Creators\FieldDefinitionCreator;
use Serafim\Railgun\Schema\Fields;

/**
 * Interface ObjectTypeInterface
 * @package Serafim\Railgun\Types
 */
interface ObjectTypeInterface extends TypeInterface
{
    /**
     * @param Fields $fields
     * @return iterable|FieldDefinitionCreator[]
     */
    public function getFields(Fields $fields): iterable;

    /**
     * @return iterable|string[]
     */
    public function getInterfaces(): iterable;
}
