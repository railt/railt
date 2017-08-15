<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Abstraction;

/**
 * Interface SchemaTypeInterface
 * @package Railgun\Reflection\Abstraction
 */
interface SchemaTypeInterface extends DefinitionInterface
{
    /**
     * @return ObjectTypeInterface
     */
    public function getQuery(): ObjectTypeInterface;

    /**
     * @return null|ObjectTypeInterface
     */
    public function getMutation(): ?ObjectTypeInterface;

    /**
     * @return bool
     */
    public function hasMutation(): bool;
}
