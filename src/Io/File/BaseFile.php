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
use Railt\Io\DeclarationInterface;
use Railt\Io\Exception\ExternalExceptionInterface;
use Railt\Io\Exception\ExternalFileException;
use Railt\Io\File;
use Railt\Io\Position;
use Railt\Io\PositionInterface;
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
        $this->name        = $name;
        $this->contents    = $contents;
        $this->declaration = Declaration::make(File::class, Readable::class);
    }

    /**
     * @param string $message
     * @param int $offsetOrLine
     * @param int|null $column
     * @return ExternalExceptionInterface
     */
    public function error(string $message, int $offsetOrLine = 0, int $column = null): ExternalExceptionInterface
    {
        $error = new ExternalFileException($message);
        $error->throwsIn($this, $offsetOrLine, $column);

        return $error;
    }

    /**
     * @param int $bytesOffset
     * @return Position|PositionInterface
     */
    public function getPosition(int $bytesOffset): PositionInterface
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
     * @return DeclarationInterface|Declaration
     */
    public function getDeclarationInfo(): DeclarationInterface
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
     * @return string
     */
    public function getPathname(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPathname();
    }
}
