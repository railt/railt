<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Kernel\Contracts;

use Railt\SDL\Contracts\Document;

/**
 * Interface ClassLoader
 */
interface ClassLoader
{
    /**
     * @param Document $document
     * @param string $action
     * @return array
     */
    public function action(Document $document, string $action): array;

    /**
     * @param Document $document
     * @param string $class
     * @return string
     */
    public function load(Document $document, string $class): string;
}
