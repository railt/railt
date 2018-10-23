<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer;

/**
 * Interface MultistateLexerInterface
 */
interface MultistateLexerInterface extends SimpleLexerInterface
{
    /**
     * Method for indicating the status identifier of the indicated token.
     *
     * For example, in this case, the T_STRING token will only
     * occur when the lexer is in state 1:
     *
     * <code>
     *  $lexer->add('T_QUOTE_OPEN', '"');
     *  $lexer->add('T_QUOTE_CLOSE', '"');
     *  $lexer->add('T_STRING', '[^\\"]');
     *
     *  $lexer->state('T_QUOTE_OPEN', 0, 1);
     *  $lexer->state('T_STRING', 1);
     *  $lexer->state('T_QUOTE_CLOSE', 1, 0);
     *
     *  $lexer->lex('"Hello!"');
     *  // T_QUOTE_OPEN (state 0 -> 1)
     *  // T_QUOTE_OPEN (state 1 -> 1)
     *  // T_QUOTE_CLOSE (state 1 -> 0)
     * </code>
     *
     * @param string $token Token name
     * @param int $state State identifier
     * @param int|null $nextState
     * @return MultistateLexerInterface
     */
    public function state(string $token, int $state, int $nextState = null): self;
}
