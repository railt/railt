<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Syntax;

use Railt\Component\Exception\ExternalException;
use Railt\Component\Io\File;
use PHPUnit\Framework\Exception;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use PHPUnit\Framework\AssertionFailedError;
use Railt\Component\Parser\ParserInterface;
use Railt\Component\Parser\Exception\UnexpectedTokenException;

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
     * @throws ExternalException
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
     * @throws ExternalException
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
     * @throws AssertionFailedError
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
     * @throws Exception
     */
    public function testNegativeParserSpecs(ParserInterface $parser, string $expected): void
    {
        $this->expectException(UnexpectedTokenException::class);
        $this->expectExceptionMessage('Unexpected token "broken" (T_NAME)');

        $parser->parse(File::fromSources($expected));
    }
}
