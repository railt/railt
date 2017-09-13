<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Parser\File;
use Railt\Parser\Parser;
use Railt\Tests\AbstractTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class AstABSpecsTestCase
 * @package Railt\Tests\Parser\Compiler
 * @group large
 */
class AstABSpecsTestCase extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $specDirectory = __DIR__ . '/../.resources/ast-ab-spec-tests';

    /**
     * @dataProvider positiveTests
     * @param string $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Railt\Parser\Exceptions\NotReadableException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testPositiveCompilation($file): void
    {
        $compiler = new Parser();

        $compiler->parse(File::path($file));

        $this->assertTrue(true, $file . ' compilation fail');
    }

    /**
     * @dataProvider negativeTests
     * @param string $file
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Parser\Exceptions\NotReadableException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testNegativeCompilation($file): void
    {
        $this->expectException(\Throwable::class);

        $compiler = new Parser();

        $ast = $compiler->parse(File::path($file));

        $this->assertFalse(true,
            $file . ' must throw an error but complete successfully: ' . "\n" . Parser::dump($ast)
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
            ->name('+*');

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
            ->name('-*');

        return $this->formatProvider($finder->getIterator());
    }
}
