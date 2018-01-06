<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Persisting;

use Railt\GraphQL\Exceptions\CompilerException;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Class EmulatingPersister
 */
class EmulatingPersister implements Persister
{
    /**
     * @var array|string[]
     */
    private $storage = [];

    /**
     * @param ReadableInterface $readable
     * @param \Closure $then
     * @return Document
     * @throws CompilerException
     */
    public function remember(ReadableInterface $readable, \Closure $then): Document
    {
        $key = $readable->getHash();

        if (! \array_key_exists($key, $this->storage)) {
            /** @var Document $document */
            $document = $then($readable);

            $this->storage[$key] = $this->encode($document);
        }

        return $this->decode($this->storage[$key]);
    }

    /**
     * @param Document $document
     * @return string
     * @throws \Railt\GraphQL\Exceptions\CompilerException
     */
    private function encode(Document $document): string
    {
        try {
            return \serialize($document);
        } catch (\Error $e) {
            $error = \sprintf('Error while entity serializing: %s', $e->getMessage());
            throw new CompilerException($error, $e->getCode(), $e);
        }
    }

    /**
     * @param string $data
     * @return Document
     */
    private function decode(string $data): Document
    {
        return \unserialize($data, [
            'allowed_classes' => true,
        ]);
    }
}
