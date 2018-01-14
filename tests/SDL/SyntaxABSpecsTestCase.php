<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use PHPUnit\Framework\AssertionFailedError;
use Railt\Io\Readable;

/**
 * Class SyntaxABSpecsTestCase
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
     * @param Readable $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testPositiveCompilation($file): void
    {
        $error = $file->getPathname() . ' must not throws an exception: ' . "\n" . $file->getContents();

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
     * @param Readable $file
     * @return void
     * @throws \Exception
     * @throws \LogicException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function testNegativeCompilation(Readable $file): void
    {
        $error = $file->getPathname() . ' must throw an error but compiled successfully: ' . "\n" . $file->getContents();

        $compilersCount = 0;
        foreach ($this->getCompilers() as $compiler) {
            ++$compilersCount;
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
