<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

return <<<TEMPLATE
<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @noinspection ALL
 */

declare(strict_types=1);

use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Grammar\RuleInterface;
use Railt\SDL\Frontend\Ast;
use Railt\TypeSystem\Value;

return [

    /**
     * -------------------------------------------------------------------------
     *  Initial State
     * -------------------------------------------------------------------------
     *
     * The initial state (initial rule identifier) of the parser.
     *
     */
    'initial' => $initial,
    
    /**
     * -------------------------------------------------------------------------
     *  Lexer Tokens
     * -------------------------------------------------------------------------
     *
     * A GraphQL document is comprised of several kinds of indivisible
     * lexical tokens defined here in a lexical grammar by patterns
     * of source Unicode characters.
     *
     * Tokens are later used as terminal symbols in a GraphQL Document
     * syntactic grammars.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-Source-Text.Lexical-Tokens
     * @var string[]
     *
     */
    'lexemes' => $lexemes,
     
    /**
     * -------------------------------------------------------------------------
     *  Lexer Ignored Tokens
     * -------------------------------------------------------------------------
     *
     * Before and after every lexical token may be any amount of ignored tokens
     * including WhiteSpace and Comment. No ignored regions of a source document
     * are significant, however otherwise ignored source characters may appear
     * within a lexical token in a significant way, for example a StringValue
     * may contain white space characters and commas.
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sec-Source-Text.Ignored-Tokens
     * @var string[]
     *
     */
    'skips' => $skips,
    
    /**
     * -------------------------------------------------------------------------
     *  Parser Grammar
     * -------------------------------------------------------------------------
     *
     * Array of transition rules for the parser.
     *
     */
    'grammar' => $grammar,
    
    /**
     * -------------------------------------------------------------------------
     *  Parser Reducers
     * -------------------------------------------------------------------------
     *
     * Array of abstract syntax tree reducers.
     *
     */
    'reducers' => $reducers,

];
TEMPLATE;
