<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer;

use Railt\Io\Readable;
use Railt\Lexer\Definition\TokenDefinition;

/**
 * Interface LexerInterface
 */
interface LexerInterface
{
    /**
     * Compiling the current state of the lexer and returning stream tokens from the source file
     *
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    public function lex(Readable $input): \Traversable;

    /**
     * @return iterable|TokenDefinition[]
     */
    public function getTokenDefinitions(): iterable;
}
