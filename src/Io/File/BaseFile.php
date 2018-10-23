<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io\File;

use Railt\Io\Declaration;
use Railt\Io\Position;
use Railt\Io\Readable;

/**
 * Class BaseFile
 */
abstract class BaseFile implements Readable
{
    /**
     * @var string
     */
    protected $contents;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Declaration
     */
    private $declaration;

    /**
     * File constructor.
     * @param string $contents
     * @param string $name
     */
    public function __construct(string $contents, string $name)
    {
        $this->declaration = Declaration::make(Readable::class);
        $this->contents    = $contents;
        $this->name        = $name;
    }

    /**
     * @param int $bytesOffset
     * @return Position
     */
    public function getPosition(int $bytesOffset): Position
    {
        return new Position($this->getContents(), $bytesOffset);
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @return Declaration
     */
    public function getDeclaration(): Declaration
    {
        return $this->declaration;
    }

    /**
     * @return void
     */
    public function __wakeup(): void
    {
        $this->declaration = Declaration::make(static::class);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return [
            'contents',
            'name',
            'hash',
        ];
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        $result = ['hash' => $this->getHash()];

        if (! $this->isFile()) {
            $result['content'] = $this->getContents();
        } else {
            $result['path'] = $this->getPathname();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->name;
    }
}
