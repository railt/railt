<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Io\File;
use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definition;
use Railt\Reflection\Contracts\Document;

/**
 * Class BaseDefinition
 */
abstract class BaseDefinition implements Definition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Document
     */
    protected $document;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var File
     */
    protected $file;

    /**
     * @return string
     */
    public function getName(): string
    {
        \assert(\is_string($this->name),
            'Definition Name must be initialized');

        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        \assert(\is_string($this->description),
            'Definition Description must be initialized');

        return $this->description;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        \assert($this->document instanceof Document,
            'Definition Description must be initialized');

        return $this->document;
    }

    /**
     * @return Position
     */
    public function getDeclarationInfo(): Position
    {
        \assert(\is_int($this->offset),
            'Definition Offset must be initialized');

        return new Position($this->getFile()->getContents(), $this->offset);
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        \assert(\is_int($this->offset),
            'Definition File must be initialized');

        return $this->file;
    }
}
