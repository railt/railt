<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Lexer\TokenInterface;
use Railt\Io\Readable;

/**
 * Interface LexerInterface
 */
interface LexerInterface
{
    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    public function lex(Readable $input): \Traversable;

    /**
     * @param bool $keep
     * @return LexerInterface
     */
    public function eof(bool $keep = true): LexerInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
