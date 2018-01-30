<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Reflection\Contracts\Document;

/**
 * @deprecated This is an experimental schema packer.
 * @internal DO NOT USE IT IN PRODUCTION!!!
 */
class Artifact
{
    private const UNDEFINED_VERSION = 'dev-master';

    /**
     * @var string|int
     */
    private $version;

    /**
     * Artifact constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->version = \array_get($config, 'version', self::UNDEFINED_VERSION);
    }

    /**
     * @param Document $document
     * @return array
     */
    private function toArray(Document $document): array
    {
        return [
            'version' => $this->version,
            'body'    => \serialize($document),
        ];
    }

    /**
     * @param Document $document
     * @return string
     */
    public function pack(Document $document): string
    {
        $data = \json_encode($this->toArray($document));
        $data = \bin2hex($data);

        return \pack('h*', $data);
    }

    /**
     * @param string $data
     * @return Document
     */
    public function unpack(string $data): Document
    {
        $data  = \implode('', \unpack('h*', $data));
        $data  = \hex2bin($data);
        $data  = \json_decode($data);

        return \unserialize($data->body, []);
    }
}
