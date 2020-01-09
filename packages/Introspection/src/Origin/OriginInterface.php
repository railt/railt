<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Origin;

/**
 * Interface OriginInterface
 */
interface OriginInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return array
     */
    public function load(): array;
}
