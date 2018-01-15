<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Syntax;

use Railt\Compiler\Exception\UnexpectedTokenException;
use Railt\Io\File;
use Railt\SDL\Parser\Factory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class SpecTestCase
 */
class SpecTestCase extends AbstractSyntaxTestCase
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
            foreach ($this->getParsers() as $parser) {
                $result[] = [$parser, $file->getContents()];
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
            foreach ($this->getParsers() as $parser) {
                $result[] = [$parser, $file->getContents()];
            }
        }

        return $result;
    }

    /**
     * @dataProvider positiveProvider
     *
     * @param Factory $parser
     * @param string $expected
     * @return void
     */
    public function testPositiveParserSpecs(Factory $parser, string $expected): void
    {
        $parser->parse(File::fromSources($expected));

        $this->assertTrue(true, 'Successful compilation');
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param Factory $parser
     * @param string $expected
     * @return void
     */
    public function testNegativeParserSpecs(Factory $parser, string $expected): void
    {
        $this->expectException(UnexpectedTokenException::class);

        $parser->parse(File::fromSources($expected));
    }
}
