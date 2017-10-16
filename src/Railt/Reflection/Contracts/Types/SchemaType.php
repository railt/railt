<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

/**
 * Interface SchemaType
 */
interface SchemaType extends TypeDefinition
{
    /**
     * @return ObjectType
     */
    public function getQuery(): ObjectType;

    /**
     * @return null|ObjectType
     */
    public function getMutation(): ?ObjectType;

    /**
     * @return bool
     */
    public function hasMutation(): bool;

    /**
     * @return null|ObjectType
     */
    public function getSubscription(): ?ObjectType;

    /**
     * @return bool
     */
    public function hasSubscription(): bool;
}
