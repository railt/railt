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
use Railt\SDL\Parser\Factory;

/**
 * Class AstABSpecsTestCase
 * @group large
 */
class AstABSpecsTestCase extends AbstractCompilerTestCase
{
    /**
     * @var string
     */
    protected $specDirectory = __DIR__ . '/.resources/ast-spec-tests';

    /**
     * @dataProvider loadPositiveABTests
     *
     * @param Readable $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testPositiveCompilation(Readable $file): void
    {
        $error = $file->getPathname() . ' must not throws an exception: ' . "\n" . $file->getContents();

        try {
            $compiler = new Factory();
            $compiler->parse($file);
        } catch (\Throwable $e) {
            static::throwException(new AssertionFailedError($error));
        }

        static::assertTrue(true);
    }

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
     * @dataProvider loadNegativeABTests
     *
     * @param Readable $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function testNegativeCompilation(Readable $file): void
    {
        $error = $file->getPathname() . ' must throw an error but compiled successfully: ' . "\n" .
            $file->getContents();

        try {
            $compiler = new Factory();
            $ast      = $compiler->parse($file);
        } catch (\Throwable $e) {
            static::assertTrue(true);
            return;
        }

        static::assertFalse(true, $error . "\n" . $compiler->dump($ast));
    }
}
