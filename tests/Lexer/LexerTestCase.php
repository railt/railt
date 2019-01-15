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
use Railt\Lexer\LexerInterface;
use Railt\Lexer\Result\Eoi;
use Railt\Lexer\Result\Unknown;

/**
 * Class LexerCompiler
 */
abstract class LexerTestCase extends BaseTestCase
{
    /**
     * @return array
     */
    abstract public function provider(): array;

    /**
     * @dataProvider provider
     * @param LexerInterface $lexer
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDigits(LexerInterface $lexer): void
    {
        $result = \iterator_to_array($lexer->lex(File::fromSources('23 42')));

        $this->assertCount(3, $result);
        $this->assertSame('T_DIGIT', $result[0]->getName());
        $this->assertSame('T_DIGIT', $result[1]->getName());
        $this->assertSame(Eoi::T_NAME, $result[2]->getName());
    }

    /**
     * @dataProvider provider
     * @param LexerInterface $lexer
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDigitsWithSkipped(LexerInterface $lexer): void
    {
        $lexer = clone $lexer;
        $lexer->skip(Eoi::T_NAME);
        $result = \iterator_to_array($lexer->lex(File::fromSources('23 42')));

        $this->assertCount(2, $result);
        $this->assertSame('T_DIGIT', $result[0]->getName());
        $this->assertSame('T_DIGIT', $result[1]->getName());
    }

    /**
     * @dataProvider provider
     * @param LexerInterface $lexer
     * @throws \PHPUnit\Framework\Exception
     */
    public function testUnknownLookahead(LexerInterface $lexer): void
    {
        $file = File::fromSources("23 \nunknown \n42");
        $result = \iterator_to_array($lexer->lex($file));

        $this->assertCount(4, $result);
        $this->assertSame('T_DIGIT', $result[0]->getName());
        $this->assertSame('T_UNKNOWN', $result[1]->getName());
        $this->assertSame('T_DIGIT', $result[2]->getName());
        $this->assertSame(Eoi::T_NAME, $result[3]->getName());

        /** @var Unknown $unknown */
        $unknown = $result[1];

        $this->assertSame(4, $unknown->getOffset(), 'Bad Offset');
        $this->assertSame(7, $unknown->getLength(), 'Bad Length');
    }

    /**
     * @param iterable|\Traversable|array $items
     * @return array
     */
    protected function toArray(iterable $items): array
    {
        return \is_array($items) ? $items : \iterator_to_array($items);
    }

    /**
     * @dataProvider provider
     * @param LexerInterface $lexer
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testStatelessLexWithAddedToken(LexerInterface $lexer): void
    {
        if (! ($lexer instanceof Stateless)) {
            $this->markTestSkipped('This test is only available for stateless lexers');
        }

        $lexer->add('T_WORD', '\w+');
        $result = \iterator_to_array($lexer->lex(File::fromSources('23 42 word word'), ['T_WHITESPACE', Eoi::T_NAME]));

        $this->assertCount(4, $result);
    }

    /**
     * @dataProvider provider
     * @param LexerInterface|Stateless $lexer
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testStatelessLexWithSkippedToken(LexerInterface $lexer): void
    {
        if (! ($lexer instanceof Stateless)) {
            $this->markTestSkipped('This test is only available for stateless lexers');
        }

        $lexer->add('T_WORD', '\w+');
        $result = \iterator_to_array($lexer->lex(File::fromSources('23 word word 42'), ['T_WHITESPACE', Eoi::T_NAME]));

        $this->assertCount(4, $result);
    }

    /**
     * @dataProvider provider
     * @param LexerInterface $lexer
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testStatelessCheckAddedToken(LexerInterface $lexer): void
    {
        if (! ($lexer instanceof Stateless)) {
            $this->markTestSkipped('This test is only available for stateless lexers');
        }

        $lexer->add('T_WORD', '\w+');

        $this->assertTrue($lexer->has('T_DIGIT'));
        $this->assertTrue($lexer->has('T_WHITESPACE'));
        $this->assertTrue($lexer->has('T_WORD'));
        $this->assertFalse($lexer->has(Unknown::T_NAME));
        $this->assertFalse($lexer->has(Eoi::T_NAME));
    }

    /**
     * @dataProvider provider
     * @param LexerInterface $lexer
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testStatelessCheckAddedSkippedToken(LexerInterface $lexer): void
    {
        if (! ($lexer instanceof Stateless)) {
            $this->markTestSkipped('This test is only available for stateless lexers');
        }

        $lexer->add('T_WORD', '\w+');

        $this->assertTrue($lexer->has('T_DIGIT'));
        $this->assertTrue($lexer->has('T_WHITESPACE'));
        $this->assertTrue($lexer->has('T_WORD'));
        $this->assertFalse($lexer->has(Unknown::T_NAME));
        $this->assertFalse($lexer->has(Eoi::T_NAME));
    }
}
