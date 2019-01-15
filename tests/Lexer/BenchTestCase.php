<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Lexer;

use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Lexer\Driver\NativeRegex;
use Railt\Lexer\Driver\ParleLexer;
use Railt\Lexer\LexerInterface;

/**
 * Class BenchTestCase
 */
class BenchTestCase extends BaseTestCase
{
    private const TEMPLATE =
        '
%s:
| Sample        | %s (%d tokens per file, %d iterations)
| Time          | %01.5fs
| AVG           | %01.5fs
| Token/s       | %d

';

    /**
     * @return array
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function benchesProvider(): array
    {
        return [
            'Little (1000 iterations)' => [1000, File::fromPathname(__DIR__ . '/resources/little.txt')],
            'Average (100 iterations)' => [100, File::fromPathname(__DIR__ . '/resources/average.txt')],
            'Large (10 iterations)'    => [10, File::fromPathname(__DIR__ . '/resources/large.txt')],
        ];
    }

    /**
     * @dataProvider benchesProvider
     * @param int $samples
     * @param Readable $sources
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    public function testParleLexer(int $samples, Readable $sources): void
    {
        $tokens = require __DIR__ . '/resources/graphql.lex.php';
        $lexer = new ParleLexer();

        foreach ($tokens as $token => $pcre) {
            $lexer->add($token, $pcre);
        }

        $this->execute($lexer, $samples, $sources);
    }

    /**
     * @param int $samples
     * @param LexerInterface $lexer
     * @param Readable $sources
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    private function execute(LexerInterface $lexer, int $samples, Readable $sources): void
    {
        $cnt = 0;
        $results = [];

        for ($i = 0; $i < $samples; ++$i) {
            $start = \microtime(true);

            $cnt += \count(\iterator_to_array($lexer->lex($sources)));

            $results[] = \microtime(true) - $start;
        }

        $this->write($lexer, $results, $cnt, $sources);
        $this->assertTrue(true);
    }

    /**
     * @param LexerInterface $lexer
     * @param array $results
     * @param int $tokens
     * @param Readable $sources
     */
    private function write(LexerInterface $lexer, array $results, int $tokens, Readable $sources): void
    {
        $sum = \array_sum($results);
        $avg = $sum / \count($results);

        echo \vsprintf(self::TEMPLATE, [
            \basename(\str_replace('\\', '/', \get_class($lexer))),
            \basename($sources->getPathname()),
            $tokens / \count($results),
            \count($results),
            $sum,                       /* SUM */
            $avg,                       /* AVG */
            $tokens / $sum,              /* TPS */
        ]);
        \flush();
    }

    /**
     * @dataProvider benchesProvider
     * @param int $samples
     * @param Readable $sources
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testNativeRegexLexer(int $samples, Readable $sources): void
    {
        $tokens = require __DIR__ . '/resources/graphql.lex.php';

        $lexer = new NativeRegex($tokens);

        $this->execute($lexer, $samples, $sources);
    }
}
