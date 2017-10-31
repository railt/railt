<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Parser;
use Railt\Compiler\Filesystem\File;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
     * @dataProvider positiveTests
     *
     * @param $file
     * @throws \Hoa\Compiler\Exception\UnrecognizedToken
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testPositiveCompilation($file): void
    {
        $compiler = new Parser();

        $compiler->parse(File::fromPathname($file));
        $this->assertTrue(true);
    }

    /**
     * @dataProvider negativeTests
     *
     * @param $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     */
    public function testNegativeCompilation($file): void
    {
        $this->expectException(\Throwable::class);

        $compiler = new Parser();

        $ast = $compiler->parse(File::fromPathname($file));

        $this->assertFalse(true,
            $file . ' must throw an error but complete successfully: ' . "\n" .
            $compiler->dump($ast)
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function positiveTests(): array
    {
        $finder = (new Finder())
            ->files()
            ->in($this->specDirectory)
            ->name('\+.*?\.graphqls');

        return $this->formatProvider($finder->getIterator());
    }

    /**
     * @param \Traversable|SplFileInfo[] $files
     * @return array
     */
    private function formatProvider(\Traversable $files): array
    {
        $tests = [];

        foreach ($files as $test) {
            $tests[] = [$test->getRealPath()];
        }

        return $tests;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function negativeTests(): array
    {
        $finder = (new Finder())
            ->files()
            ->in($this->specDirectory)
            ->name('\-.*?\.graphqls');

        return $this->formatProvider($finder->getIterator());
    }
}
