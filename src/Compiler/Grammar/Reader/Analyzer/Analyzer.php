<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader\Analyzer;

use Railt\Compiler\LexerInterface;

/**
 * Interface Analyzer
 */
interface Analyzer
{
    /**
     * Analyzer constructor.
     * @param LexerInterface $lexer
     */
    public function __construct(LexerInterface $lexer);

    /**
     * @param iterable $rules
     * @return iterable
     */
    public function analyze(iterable $rules): iterable;
}
