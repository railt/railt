<?php
/**
 * This file is part of Io package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\Exceptions\NotFoundException;
use Railt\Io\Exceptions\NotReadableException;
use Railt\Io\File;

/**
 * Class NegativeTestCase
 */
class NegativeIoTestCase extends AbstractIoTestCase
{
    /**
     * @return void
     */
    public function testFileNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('File "Example" not found');

        File::fromPathname('Example');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testPermissionsDenied(): void
    {
        if (\stripos(\PHP_OS, 'WIN') === 0) {
            $this->markTestSkipped('OS filesystem does not support permissions');
        }

        $this->expectException(NotReadableException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('File "Example" not found');

        $file = __DIR__ . '/.resources/' .
            \sha1((string)\random_int(\PHP_INT_MIN, \PHP_INT_MAX))
            . '.locked';

        \file_put_contents($file, '');
        \chmod($file, 0333);

        File::fromPathname($file);

        \chmod($file, 0777);
        \unlink($file);
    }
}
