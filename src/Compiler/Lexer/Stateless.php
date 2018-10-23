<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

/**
 * Interface Lexer
 */
interface Stateless extends Stateful
{
    /**
     * Specifying the names of tokens that should be ignored on return.
     *
     * @param string $token Token name
     * @return Stateless
     */
    public function skip(string $token): self;

    /**
     * Add a lexer rule
     *
     * @param string $token Token name
     * @param string $pcre Perl compatible regular expression used for token matching
     * @return Stateless|$this
     */
    public function add(string $token, string $pcre): self;

    /**
     * Returns a list of registered tokens in "$name => $pcre" format.
     *
     * @return iterable
     */
    public function getTokens(): iterable;

    /**
     * @return iterable
     */
    public function getIgnoredTokens(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
