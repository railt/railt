<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Io\File;

use Railt\Component\Io\Exception\NotFoundException;
use Railt\Component\Io\Exception\NotReadableException;

/**
 * Class Physical
 */
class Physical extends AbstractFile
{
    /**
     * @var string|null
     */
    protected $hash;

    /**
     * Physical constructor.
     *
     * @param string $pathname
     * @throws NotFoundException
     * @throws NotReadableException
     */
    public function __construct(string $pathname)
    {
        $this->assertExists($pathname);
        $this->assertReadable($pathname);

        parent::__construct(\realpath($pathname));
    }

    /**
     * @param string $name
     * @throws NotFoundException
     */
    private function assertExists(string $name): void
    {
        if (! \is_file($name)) {
            $error = 'File "%s" not found';
            throw new NotFoundException(\sprintf($error, $name));
        }
    }

    /**
     * @param string $name
     * @throws NotReadableException
     */
    private function assertReadable(string $name): void
    {
        if (! \is_readable($name)) {
            $error = 'Can not read the file "%s": Permission denied';
            throw new NotReadableException(\sprintf($error, \realpath($name)));
        }
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null) {
            $this->hash = \sha1($this->getPathname() . ':' . \filemtime($this->getPathname()));
        }

        return $this->hash;
    }

    /**
     * @return string
     * @throws NotReadableException
     */
    public function getContents(): string
    {
        return $this->wrap(static function (string $pathname) {
            return @\file_get_contents($pathname);
        });
    }

    /**
     * @param \Closure $operation
     * @return mixed
     * @throws NotReadableException
     */
    private function wrap(\Closure $operation)
    {
        $level = \error_reporting(0);
        $result = $operation($this->getPathname());
        \error_reporting($level);

        if ($result === false) {
            throw new NotReadableException(\error_get_last()['message']);
        }

        return $result;
    }

    /**
     * @param bool $exclusive
     * @return resource
     * @throws NotReadableException
     */
    public function getStreamContents(bool $exclusive = false)
    {
        $stream = $this->wrap(static function (string $pathname) {
            return @\fopen($pathname, 'rb');
        });

        if ($exclusive) {
            \flock($stream, \LOCK_SH);
        }

        return $stream;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'hash',
        ]);
    }
}
