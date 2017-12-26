<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Hoa\Compiler\Io;

/**
 * Class File
 */
abstract class File implements Writable
{
    /**
     * @var string
     */
    private $contents;

    /**
     * @var string
     */
    private $name;

    /**
     * File constructor.
     * @param string $contents
     * @param string $name
     */
    public function __construct(string $contents, string $name)
    {
        $this->contents = $contents;
        $this->name = $name;
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
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @param string $content
     * @return Writable
     */
    public function update(string $content): Writable
    {
        return new static($content, $this->name);
    }
}
