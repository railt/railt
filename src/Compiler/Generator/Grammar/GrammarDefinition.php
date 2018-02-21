<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar;

use Railt\Compiler\Generator\Pragma;

/**
 * Interface GrammarDefinition
 */
interface GrammarDefinition
{
    /**
     * Returns a list of definitions of all tokens
     * inside the grammar in the format:
     *
     * <code>
     *  return [
     *      'TOKEN_NAME'   => ['PCRE_PATTERN', 'TOKEN_CHANNEL_NAME'],
     *      'TOKEN_NAME_2' => ['PCRE_PATTERN', 'TOKEN_CHANNEL_NAME'],
     *      ...
     *  ];
     * </code>
     *
     * @return iterable|array[]
     */
    public function getTokenDefinitions(): iterable;

    /**
     * TODO
     *
     * @return iterable
     */
    public function getRuleDefinitions(): iterable;

    /**
     * Returns a list of all declared rules for parsing the source.
     *
     * @return iterable|Pragma
     */
    public function getPragmaDefinitions(): Pragma;
}
