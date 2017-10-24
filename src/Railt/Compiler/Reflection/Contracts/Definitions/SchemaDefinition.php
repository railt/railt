<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Definitions;

/**
 * Interface SchemaDefinition
 */
interface SchemaDefinition extends Definition
{
    /**
     * @return ObjectDefinition
     */
    public function getQuery(): ObjectDefinition;

    /**
     * @return null|ObjectDefinition
     */
    public function getMutation(): ?ObjectDefinition;

    /**
     * @return bool
     */
    public function hasMutation(): bool;

    /**
     * @return null|ObjectDefinition
     */
    public function getSubscription(): ?ObjectDefinition;

    /**
     * @return bool
     */
    public function hasSubscription(): bool;
}
