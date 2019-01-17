<?php
/**
 * This file is part of compiler package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Parser\Ast\Dumper\HoaDumper;
use Railt\Parser\ParserInterface;

/**
 * Class PP2ParserTestCase
 */
class ParserTestCase extends TestCase
{
    /**
     * @return array
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function grammars(): array
    {
        $sources = [
            'PP2'  => [new PP2Llk(), __DIR__ . '/pp2/*.pp2'],
            'JSON' => [new JsonLlk(), __DIR__ . '/json/*.json'],
            'SDL'  => [new SdlLlk(), __DIR__ . '/sdl/*.graphqls'],
        ];

        $result = [];

        foreach ($sources as $name => [$parser, $directory]) {
            foreach (\glob($directory) as $file) {
                $result[$name . ':' . \basename($file)] = [$parser, File::fromPathname($file)];
            }
        }

        return $result;
    }

    /**
     * @dataProvider grammars
     * @param ParserInterface $parser
     * @param Readable $file
     * @throws \PHPUnit\Framework\Exception
     */
    public function testCompareAst(ParserInterface $parser, Readable $file): void
    {
        $ast = $parser->parse($file);
        $this->assertStringEqualsFile($file->getPathname() . '.txt', (new HoaDumper($ast))->toString() . "\n");
    }
}
