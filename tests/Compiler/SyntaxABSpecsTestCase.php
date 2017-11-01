<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use PHPUnit\Framework\AssertionFailedError;
use Railt\Compiler\Filesystem\ReadableInterface;

/**
 * Class SyntaxABSpecsTestCase
 * @package Railt\Tests\Compiler\Compiler
 * @group large
 */
class SyntaxABSpecsTestCase extends AbstractCompilerTestCase
{
    /**
     * @var string
     */
    protected $specDirectory = __DIR__ . '/.resources/syntax-spec-tests';

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function provider(): array
    {
        return \array_merge($this->loadNegativeABTests(), $this->loadPositiveABTests());
    }

    /**
     * @dataProvider loadPositiveABTests
     *
     * @param ReadableInterface $file
     * @throws \Hoa\Compiler\Exception\UnrecognizedToken
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testPositiveCompilation($file): void
    {
        $error = $file->getPathname() . ' must not throws an exception: ' . "\n" . $file->read();

        foreach ($this->getCompilers() as $compiler) {
            try {
                $compiler->compile($file);
            } catch (\Throwable $e) {
                static::throwException(new AssertionFailedError($error));
            }

            static::assertTrue(true);
        }
    }

    /**
     * @dataProvider loadNegativeABTests
     *
     * @param ReadableInterface $file
     * @return void
     * @throws \Exception
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function testNegativeCompilation(ReadableInterface $file): void
    {
        $error = $file->getPathname() . ' must throw an error but compiled successfully: ' . "\n" . $file->read();

        $compilersCount = 0;
        foreach ($this->getCompilers() as $compiler) {
            $compilersCount++;
            try {
                $compiler->compile($file);
            } catch (\Throwable $e) {
                static::assertTrue(true);
                continue;
            }

            static::assertTrue(true, $error);
        }

        static::assertEquals(6, $compilersCount);
    }
}
