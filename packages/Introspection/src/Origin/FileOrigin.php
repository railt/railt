<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Origin;

use Railt\Introspection\Exception\OriginException;

/**
 * Class FileOrigin
 */
class FileOrigin extends Origin
{
    /**
     * @var string
     */
    private string $pathname;

    /**
     * @var array
     */
    private array $context;

    /**
     * FileOrigin constructor.
     *
     * @param string $pathname
     * @param array $context
     */
    public function __construct(string $pathname, array $context = [])
    {
        \assert(\is_file($pathname));
        \assert(\is_readable($pathname));

        $this->pathname = $pathname;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return \hash('crc32', \realpath($this->pathname));
    }

    /**
     * @return array
     * @throws OriginException
     */
    public function load(): array
    {
        $result = $this->read($this->pathname, $this->context);

        return $this->decode($result);
    }
}
