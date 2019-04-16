<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Component\Lexer\Driver\NativeRegex;
use Railt\Component\Lexer\LexerInterface;
use Railt\Component\Parser\Driver\Llk;
use Railt\Component\Parser\Driver\Stateful;
use Railt\Component\Parser\Grammar;
use Railt\Component\Parser\ParserInterface;
use Railt\Component\Parser\Rule\Alternation;
use Railt\Component\Parser\Rule\Concatenation;
use Railt\Component\Parser\Rule\Repetition;
use Railt\Component\Parser\Rule\Terminal;

/**
 * This is generated file.
 * Do not update it manually.
 * Generated at 06-07-2018 13:57:25
 */
class PP2Llk extends Stateful
{
    /**
     * @return ParserInterface
     * @throws \Railt\Component\Parser\Exception\GrammarException
     */
    protected function boot(): ParserInterface
    {
        return new Llk($this->getLexer(), new Grammar([
            new Repetition(0, 0, -1, '__definition', null),
            new Concatenation('Grammar', [0], 'Grammar'),
            new Alternation('__definition', ['TokenDefinition', 'PragmaDefinition', 'IncludeDefinition', 'RuleDefinition'], null),
            new Terminal(3, 'T_TOKEN', true),
            new Concatenation(4, [3], 'TokenDefinition'),
            new Terminal(5, 'T_SKIP', true),
            new Concatenation(6, [5], 'TokenDefinition'),
            new Alternation('TokenDefinition', [4, 6], null),
            new Terminal(8, 'T_PRAGMA', true),
            new Concatenation('PragmaDefinition', [8], 'PragmaDefinition'),
            new Terminal(10, 'T_INCLUDE', true),
            new Concatenation('IncludeDefinition', [10], 'IncludeDefinition'),
            new Repetition(12, 0, 1, 'ShouldKeep', null),
            new Repetition(13, 0, 1, 'Alias', null),
            new Repetition(14, 0, 1, 'RuleDelegate', null),
            new Concatenation('RuleDefinition', [12, 'RuleName', 13, 14, 'RuleProduction'], 'RuleDefinition'),
            new Terminal(16, 'T_NAME', true),
            new Concatenation('RuleName', [16, '__ruleProductionDelimiter'], 'RuleName'),
            new Terminal(18, 'T_ALIAS', false),
            new Terminal(19, 'T_NAME', true),
            new Concatenation('Alias', [18, 19], 'Alias'),
            new Terminal(21, 'T_DELEGATE', false),
            new Terminal(22, 'T_NAME', true),
            new Concatenation('RuleDelegate', [21, 22], 'RuleDelegate'),
            new Terminal(24, 'T_KEPT_NAME', false),
            new Concatenation('ShouldKeep', [24], 'ShouldKeep'),
            new Terminal(26, 'T_COLON', false),
            new Terminal(27, 'T_EQ', false),
            new Alternation('__ruleProductionDelimiter', [26, 27], null),
            new Concatenation('RuleProduction', ['__alternation'], null),
            new Alternation('__alternation', ['__concatenation', 'Alternation'], null),
            new Terminal(31, 'T_OR', false),
            new Concatenation(32, [31, '__concatenation'], 'Alternation'),
            new Repetition(33, 1, -1, 32, null),
            new Concatenation('Alternation', ['__concatenation', 33], null),
            new Alternation('__concatenation', ['__repetition', 'Concatenation'], null),
            new Repetition(36, 1, -1, '__repetition', null),
            new Concatenation('Concatenation', ['__repetition', 36], 'Concatenation'),
            new Alternation('__repetition', ['__simple', 'Repetition'], null),
            new Concatenation('Repetition', ['__simple', 'Quantifier'], 'Repetition'),
            new Terminal(40, 'T_GROUP_OPEN', false),
            new Terminal(41, 'T_GROUP_CLOSE', false),
            new Concatenation(42, [40, '__alternation', 41], null),
            new Terminal(43, 'T_TOKEN_SKIPPED', true),
            new Terminal(44, 'T_TOKEN_KEPT', true),
            new Terminal(45, 'T_INVOKE', true),
            new Alternation('__simple', [42, 43, 44, 45], null),
            new Terminal(47, 'T_REPEAT_ZERO_OR_ONE', true),
            new Concatenation(48, [47], 'Quantifier'),
            new Terminal(49, 'T_REPEAT_ONE_OR_MORE', true),
            new Concatenation(50, [49], 'Quantifier'),
            new Terminal(51, 'T_REPEAT_ZERO_OR_MORE', true),
            new Concatenation(52, [51], 'Quantifier'),
            new Terminal(53, 'T_REPEAT_N_TO_M', true),
            new Concatenation(54, [53], 'Quantifier'),
            new Terminal(55, 'T_REPEAT_ZERO_OR_MORE', true),
            new Concatenation(56, [55], 'Quantifier'),
            new Terminal(57, 'T_REPEAT_ZERO_TO_M', true),
            new Concatenation(58, [57], 'Quantifier'),
            new Terminal(59, 'T_REPEAT_N_OR_MORE', true),
            new Concatenation(60, [59], 'Quantifier'),
            new Terminal(61, 'T_REPEAT_EXACTLY_N', true),
            new Concatenation(62, [61], 'Quantifier'),
            new Alternation('Quantifier', [48, 50, 52, 54, 56, 58, 60, 62], null),
        ]));
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return new NativeRegex([
            'T_PRAGMA'              => '%pragma\\h+([\\w\\.]+)\\h+([^\\s]+)',
            'T_INCLUDE'             => '%include\\h+([^\\s]+)',
            'T_TOKEN'               => '%token\\h+(\\w+)\\h+([^\\s]+)',
            'T_SKIP'                => '%skip\\h+(\\w+)\\h+([^\\s]+)',
            'T_OR'                  => '\\|',
            'T_TOKEN_SKIPPED'       => '::(\\w+)::',
            'T_TOKEN_KEPT'          => '<(\\w+)>',
            'T_TOKEN_STRING'        => '("[^"\\\\]+(\\\\.[^"\\\\]*)*"|\'[^\'\\\\]+(\\\\.[^\'\\\\]*)*\')',
            'T_INVOKE'              => '(\\w+)\\(\\)',
            'T_GROUP_OPEN'          => '\\(',
            'T_GROUP_CLOSE'         => '\\)',
            'T_REPEAT_ZERO_OR_ONE'  => '\\?',
            'T_REPEAT_ONE_OR_MORE'  => '\\+',
            'T_REPEAT_ZERO_OR_MORE' => '\\*',
            'T_REPEAT_N_TO_M'       => '{\\h*(\\d+)\\h*,\\h*(\\d+)\\h*}',
            'T_REPEAT_N_OR_MORE'    => '{\\h*(\\d+)\\h*,\\h*}',
            'T_REPEAT_ZERO_TO_M'    => '{\\h*,\\h*(\\d+)\\h*}',
            'T_REPEAT_EXACTLY_N'    => '{\\h*(\\d+)\\h*}',
            'T_KEPT_NAME'           => '#',
            'T_NAME'                => '[a-zA-Z_\\x7f-\\xff\\\\][a-zA-Z0-9_\\x7f-\\xff\\\\]*',
            'T_EQ'                  => '::=',
            'T_ALIAS'               => '\\bas\\b',
            'T_COLON'               => ':',
            'T_DELEGATE'            => '\\->',
            'T_WHITESPACE'          => '(\\xfe\\xff|\\x20|\\x09|\\x0a|\\x0d)+',
            'T_COMMENT'             => '//[^\\n]*',
            'T_BLOCK_COMMENT'       => '/\\*.*?\\*/',
        ], ['T_WHITESPACE', 'T_COMMENT', 'T_BLOCK_COMMENT']);
    }
}
