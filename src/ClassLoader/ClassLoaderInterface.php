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
     * @param string $needle
     * @return string
     */
    public function find(Document $document, string $needle): string;
}
