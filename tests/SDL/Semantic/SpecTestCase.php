<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Semantic;

use Railt\Io\File;
use Railt\SDL\Compiler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class SpecTestCase
 */
class SpecTestCase extends AbstractSemanticTestCase
{
    /**
     * @return array
     */
    public function positiveProvider(): array
    {
        $result = [];

        $files = (new Finder())->files()->in(__DIR__ . '/.resources')->name('+*.graphqls');

        /** @var SplFileInfo $file */
        foreach ($files->getIterator() as $file) {
            foreach ($this->getCompilers() as $compiler) {
                $result[] = [$compiler, $file->getContents()];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function negativeProvider(): array
    {
        $result = [];

        $files = (new Finder())->files()->in(__DIR__ . '/.resources')->name('-*.graphqls');

        /** @var SplFileInfo $file */
        foreach ($files->getIterator() as $file) {
            foreach ($this->getCompilers() as $compiler) {
                $result[] = [$compiler, $file->getContents()];
            }
        }

        return $result;
    }

    /**
     * @dataProvider positiveProvider
     *
     * @param Compiler $compiler
     * @param string $expected
     * @return void
     */
    public function testPositiveParserSpecs(Compiler $compiler, string $expected): void
    {
        $compiler->compile(File::fromSources($expected));

        $this->assertTrue(true, 'Successful compilation');
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param Compiler $compiler
     * @param string $expected
     * @return void
     */
    public function testNegativeParserSpecs(Compiler $compiler, string $expected): void
    {
        $this->expectException(\Exception::class);

        $compiler->compile(File::fromSources($expected));
    }
}
