<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Persisting;

use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Filesystem\ReadableInterface;

/**
 * Interface Persister
 */
interface Persister
{
    /**
     * @param ReadableInterface $readable
     * @param \Closure $then
     * @return Document
     */
    public function remember(ReadableInterface $readable, \Closure $then): Document;
}
