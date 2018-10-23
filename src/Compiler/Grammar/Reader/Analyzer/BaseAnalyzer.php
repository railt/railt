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
 * Class BaseAnalyzer
 */
abstract class BaseAnalyzer implements Analyzer
{
    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * BaseAnalyzer constructor.
     * @param LexerInterface $lexer
     */
    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * @return LexerInterface
     */
    protected function getLexer(): LexerInterface
    {
        return $this->lexer;
    }
}
