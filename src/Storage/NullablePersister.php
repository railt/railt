<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Document;

/**
 * Class NullablePersister
 */
class NullablePersister implements Persister
{
    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return Document
     */
    public function remember(Readable $readable, \Closure $then): Document
    {
        return $then($readable);
    }
}
