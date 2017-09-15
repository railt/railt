<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support\Filesystem;

/**
 * Interface ReadableInterface
 * @package Railt\Support\Filesystem
 */
interface ReadableInterface
{
    /**
     * @return string
     */
    public function getPathname()/*: string*/;

    /**
     * @return string
     */
    public function read(): string;
}
