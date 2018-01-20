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
 * Interface LexerInterface
 */
interface LexerInterface extends \IteratorAggregate
{
    /**
     * @param bool $keep
     * @return LexerInterface
     */
    public function keepAll(bool $keep = false): self;
}
