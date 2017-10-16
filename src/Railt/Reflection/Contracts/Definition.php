<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts;

/**
 * Interface Definition
 */
interface Definition
{
    /**
     * @return Document
     */
    public function getDocument(): Document;

    /**
     * A unique identifier for the type is needed to identify the entity
     * in cases of type names conflicts.
     *
     * @return string Type identifier
     */
    public function getUniqueId(): string;
}
