<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Lexer;

/**
 * Interface SimpleLexerInterface
 */
interface SimpleLexerInterface extends LexerInterface
{
    /**
     * LexerInterface constructor.
     *
     * @param array $tokens
     * @param array $skip
     */
    public function __construct(array $tokens = [], array $skip = []);

    /**
     * Add a lexer rule
     *
     * @param string $token Token name
     * @param string $pcre Perl compatible regular expression used for token matching
     * @return LexerInterface|$this
     */
    public function add(string $token, string $pcre): LexerInterface;

    /**
     * A method for marking a token as skipped.
     *
     * @param string $name Token name
     * @return LexerInterface
     */
    public function skip(string $name): LexerInterface;
}
