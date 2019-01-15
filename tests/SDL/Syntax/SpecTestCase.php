<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Syntax;

use Railt\Io\File;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\ParserInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class SpecTestCase
 */
class SpecTestCase extends AbstractSyntaxTestCase
{
    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function positiveProvider(): array
    {
        $result = [];

        $files = (new Finder())->files()->in(__DIR__ . '/resources')->name('+*.graphqls');

        /** @var SplFileInfo $file */
        foreach ($files->getIterator() as $file) {
            foreach ($this->getParsers() as $name => $parser) {
                $result[$name . ': ' . $file->getBasename()] = [$parser, $file->getContents()];
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function negativeProvider(): array
    {
        $result = [];

        $files = (new Finder())->files()->in(__DIR__ . '/resources')->name('-*.graphqls');

        /** @var SplFileInfo $file */
        foreach ($files->getIterator() as $file) {
            foreach ($this->getParsers() as $name => $parser) {
                $result[$name . ': ' . $file->getBasename()] = [$parser, $file->getContents()];
            }
        }

        return $result;
    }

    /**
     * @dataProvider positiveProvider
     *
     * @param ParserInterface $parser
     * @param string $expected
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testPositiveParserSpecs(ParserInterface $parser, string $expected): void
    {
        $parser->parse(File::fromSources($expected));

        $this->assertTrue(true, 'Successful compilation');
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param ParserInterface $parser
     * @param string $expected
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNegativeParserSpecs(ParserInterface $parser, string $expected): void
    {
        $this->expectException(UnexpectedTokenException::class);
        $this->expectExceptionMessage('Unexpected token "broken" (T_NAME)');

        $parser->parse(File::fromSources($expected));
    }
}
