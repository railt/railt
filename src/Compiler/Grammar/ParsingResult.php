<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Railt\Compiler\Grammar\Reader\PragmaParser;
use Railt\Compiler\Grammar\Reader\ProductionParser;
use Railt\Compiler\Grammar\Reader\RuleAnalyzer;
use Railt\Compiler\Grammar\Reader\TokenParser;
use Railt\Compiler\Lexer;
use Railt\Compiler\Lexer\Runtime as LexerRuntime;
use Railt\Compiler\LexerInterface;
use Railt\Compiler\Parser;
use Railt\Compiler\Parser\Runtime as ParserRuntime;
use Railt\Compiler\ParserInterface;

/**
 * Class Result
 */
class ParsingResult
{
    /**
     * @var PragmaParser
     */
    private $pragma;

    /**
     * @var TokenParser
     */
    private $tokens;

    /**
     * @var ProductionParser
     */
    private $productions;

    /**
     * @var LexerInterface|LexerRuntime
     */
    private $lexer;

    /**
     * @var ParserInterface|ParserRuntime
     */
    private $parser;

    /**
     * Result constructor.
     * @param PragmaParser $pragma
     * @param TokenParser $tokens
     * @param ProductionParser $productions
     */
    public function __construct(PragmaParser $pragma, TokenParser $tokens, ProductionParser $productions)
    {
        $this->pragma      = $pragma;
        $this->tokens      = $tokens;
        $this->productions = $productions;
    }

    /**
     * @return ParserInterface|Parser
     */
    public function getParser(): ParserInterface
    {
        if ($this->parser === null) {
            $this->parser = new Parser($this->getLexer(), $this->getRules());
        }

        return $this->parser;
    }

    /**
     * @return LexerInterface|Lexer
     */
    public function getLexer(): LexerInterface
    {
        if ($this->lexer === null) {
            $this->lexer = new Lexer($this->tokens->getTokens());
        }

        return $this->lexer;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $analyzer = new RuleAnalyzer($this->getLexer());
        
        $result = $analyzer->analyze($this->productions->getRules());

        return $result instanceof \Traversable ? \iterator_to_array($result) : $result;
    }
}
