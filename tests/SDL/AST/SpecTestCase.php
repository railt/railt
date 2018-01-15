<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\AST;

use Railt\Compiler\Debug\NodeDumper;
use Railt\Io\File;
use Railt\SDL\Parser\Factory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class SpecTestCase
 */
class SpecTestCase extends AbstractASTTestCase
{
    /**
     * @return array
     */
    public function provider(): array
    {
        $result = [];

        $files = (new Finder())->files()->in(__DIR__ . '/.resources')->name('*.phpt');

        /** @var SplFileInfo $file */
        foreach ($files->getIterator() as $file) {
            $parts = \array_map('trim',
                \preg_split('/^\-\-(TEST|FILE|EXPECTF)\-\-$/m', $file->getContents())
            );

            foreach ($this->getParsers() as $parser) {
                $result[] = \array_merge([$parser], \array_slice($parts, 1));
            }
        }

        return $result;
    }

    /**
     * @dataProvider provider
     *
     * @param Factory $parser
     * @param string $title
     * @param string $code
     * @param string $expected
     * @return void
     */
    public function testAstSpecs(Factory $parser, string $title, string $code, string $expected): void
    {
        $ast = $parser->parse(File::fromSources($code));

        $this->assertSame($expected, (new NodeDumper($ast))->toXml(), $title);
    }
}
