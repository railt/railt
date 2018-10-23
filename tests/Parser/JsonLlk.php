<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Lexer\Driver\NativeRegex;
use Railt\Lexer\LexerInterface;
use Railt\Parser\Driver\Llk;
use Railt\Parser\Driver\Stateful;
use Railt\Parser\Grammar;
use Railt\Parser\ParserInterface;
use Railt\Parser\Rule\Alternation;
use Railt\Parser\Rule\Concatenation;
use Railt\Parser\Rule\Repetition;
use Railt\Parser\Rule\Terminal;

/**
 * Class JsonLlk
 */
class JsonLlk extends Stateful
{
    /**
     * @return ParserInterface
     * @throws \Railt\Parser\Exception\GrammarException
     */
    protected function boot(): ParserInterface
    {
        return new Llk($this->getLexer(), new Grammar([
            new Terminal(0, 'true', true),
            new Terminal(1, 'false', true),
            new Terminal(2, 'null', true),
            new Alternation('value', [0, 1, 2, 'string', 'object', 'array', 'number'], null),
            new Terminal('string', 'string', true),
            new Terminal('number', 'number', true),
            new Terminal(6, 'brace_', false),
            new Repetition(7, 0, 1, 'pair', null),
            new Terminal(8, 'comma', false),
            new Concatenation(9, [8, 'pair'], 'object'),
            new Repetition(10, 0, -1, 9, null),
            new Terminal(11, '_brace', false),
            (new Concatenation('object', [6, 7, 10, 11], null))->setDefaultId('object'),
            new Terminal(13, 'colon', false),
            (new Concatenation('pair', ['string', 13, 'value'], 'pair'))->setDefaultId('pair'),
            new Terminal(15, 'bracket_', false),
            new Repetition(16, 0, 1, 'value', null),
            new Terminal(17, 'comma', false),
            new Concatenation(18, [17, 'value'], 'array'),
            new Repetition(19, 0, -1, 18, null),
            new Terminal(20, '_bracket', false),
            (new Concatenation('array', [15, 16, 19, 20], null))->setDefaultId('array'),
        ], 'value'));
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return new NativeRegex([
            'skip'     => '\s',
            'true'     => 'true',
            'false'    => 'false',
            'null'     => 'null',
            'string'   => '"[^"\\\]*(\\\.[^"\\\]*)*"',
            'brace_'   => '{',
            '_brace'   => '}',
            'bracket_' => '\[',
            '_bracket' => '\]',
            'colon'    => ':',
            'comma'    => ',',
            'number'   => '\d+',
        ], ['skip']);
    }
}
