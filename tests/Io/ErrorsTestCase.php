<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\Exception\ExternalExceptionInterface;
use Railt\Io\Exception\NotFoundException;
use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class ErrorsTestCase
 */
class ErrorsTestCase extends TestCase
{
    /**
     * @return void
     * @throws NotReadableException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNotFound(): void
    {
        $file = __DIR__ . '/not-exists.txt';

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('File "' . $file . '" not found');

        File::fromPathname($file);
    }

    /**
     * @return void
     * @throws NotReadableException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testNotReadable(): void
    {
        if (\strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            $this->markTestSkipped('Windows OS does not support the chmod options');
        }

        $file = __DIR__ . '/resources/locked';

        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('Can not read the file "' . $file . '": Permission denied');

        \file_put_contents($file, '');
        \chmod($file, 0333);

        File::fromPathname($file);

        @\chmod($file, 0777);
        @\unlink($file);
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @return void
     * @throws \Exception
     */
    public function testExternalErrorWithLineAndColumn(\Closure $factory): void
    {
        $this->expectException(ExternalExceptionInterface::class);
        $this->expectExceptionMessage($message = 'Something went wrong ' . \random_int(\PHP_INT_MIN, \PHP_INT_MAX));

        /** @var Readable $readable */
        $readable = $factory();

        try {
            throw $readable->error($message, 23, 42);
        } catch (ExternalExceptionInterface $e) {
            $this->assertSame(23, $e->getLine());
            $this->assertSame(42, $e->getColumn());

            throw $e;
        }
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @return void
     * @throws \Exception
     */
    public function testExternalErrorWithOffset(\Closure $factory): void
    {
        $this->expectException(ExternalExceptionInterface::class);
        $this->expectExceptionMessage($message = 'Something went wrong ' . \random_int(\PHP_INT_MIN, \PHP_INT_MAX));

        /** @var Readable $readable */
        $readable = $factory();

        try {
            throw $readable->error($message, 150);
        } catch (ExternalExceptionInterface $e) {
            $this->assertSame(2, $e->getLine());
            $this->assertSame(39, $e->getColumn());

            throw $e;
        }
    }

    /**
     * @return string
     */
    protected function getPathname(): string
    {
        return __DIR__ . '/resources/example.txt';
    }
}
