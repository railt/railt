<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\ClassLoader;

use Railt\SDL\Contracts\Document;

/**
 * Interface ClassLoader
 */
interface ClassLoaderInterface
{
    /**
     * @param Document $document
     * @param string $action
     * @param int $line
     * @return array
     */
    public function action(Document $document, string $action, int $line = 0): array;

    /**
     * @param Document $document
     * @param string $class
     * @param int $line
     * @return string
     */
    public function load(Document $document, string $class, int $line = 0): string;
}
