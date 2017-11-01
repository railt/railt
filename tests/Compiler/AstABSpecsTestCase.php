<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Compiler\Parser;

/**
 * Class AstABSpecsTestCase
 * @package Railt\Tests\Compiler\Compiler
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
     * @param ReadableInterface $file
     * @throws \Hoa\Compiler\Exception\UnrecognizedToken
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testPositiveCompilation($file): void
    {
        $compiler = new Parser();

        $compiler->parse($file);
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
     * @param ReadableInterface $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     */
    public function testNegativeCompilation(ReadableInterface $file): void
    {
        $this->expectException(\Throwable::class);

        $compiler = new Parser();

        $ast = $compiler->parse($file);

        static::assertFalse(true,
            $file->getPathname() . ' must throw an error but complete successfully: ' . "\n" .
            $compiler->dump($ast)
        );
    }
}
