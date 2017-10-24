<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Filesystem;

/**
 * Interface ReadableInterface
 */
interface ReadableInterface
{
    /**
     * Name of file when file name not defined
     */
    public const VIRTUAL_FILE_NAME = 'php://input';

    /**
     * @return string|mixed
     */
    public function getPathname();

    /**
     * @return string
     */
    public function getHash(): string;

    /**
     * @return string
     */
    public function read(): string;
}
