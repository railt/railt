<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts\Dependent\Field;

use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * The interface indicates that the type is a container that
 * contains a list of fields in the type.
 */
interface HasFields
{
    /**
     * @return iterable|FieldDefinition[]
     */
    public function getFields(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool;

    /**
     * @param string $name
     * @return null|FieldDefinition
     */
    public function getField(string $name): ?FieldDefinition;

    /**
     * @return int
     */
    public function getNumberOfFields(): int;
}
