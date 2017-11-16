<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Persisting;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Class ArrayPersister
 */
class ArrayPersister implements Persister
{
    /**
     * @var array|Document[]
     */
    private $storage = [];

    /**
     * @param ReadableInterface $readable
     * @param \Closure $then
     * @return Document
     */
    public function remember(ReadableInterface $readable, \Closure $then): Document
    {
        $key = $readable->getHash();

        if (!\array_key_exists($key, $this->storage)) {
            /** @var Document $document */
            $document = $then($readable);

            $this->storage[$key] = $document;
        }

        return $this->storage[$key];
    }
}
