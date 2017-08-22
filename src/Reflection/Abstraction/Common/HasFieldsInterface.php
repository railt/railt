<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Abstraction\Common;

use Railt\Reflection\Abstraction\FieldInterface;

/**
 * Interface HasFieldsInterface
 * @package Railt\Reflection\Abstraction\Common
 */
interface HasFieldsInterface
{
    /**
     * @return iterable|FieldInterface[]
     */
    public function getFields(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool;

    /**
     * @param string $name
     * @return null|FieldInterface
     */
    public function getField(string $name): ?FieldInterface;
}
