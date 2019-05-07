<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Lexer;

use Railt\Component\Lexer\Driver\ParleLexer;

/**
 * Class ParleTestCase
 */
class ParleTestCase extends LexerTestCase
{
    /**
     * @return array
     * @throws \Railt\Component\Lexer\Exception\BadLexemeException
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function provider(): array
    {
        if (! \class_exists(\Parle\Lexer::class, false)) {
            $this->markTestSkipped('Parle extension not installed');
        }

        return [
            [new ParleLexer(['T_WHITESPACE' => '\s+', 'T_DIGIT' => '\d+'], ['T_WHITESPACE'])],
        ];
    }
}
