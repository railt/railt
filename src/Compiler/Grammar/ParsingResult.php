<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar;

use Railt\Compiler\Grammar\Builder\Buildable;
use Railt\Compiler\Grammar\Reader\PragmaParser;
use Railt\Compiler\Grammar\Reader\ProductionParser;
use Railt\Compiler\Grammar\Reader\RuleAnalyzer;
use Railt\Compiler\Grammar\Reader\TokenParser;
use Railt\Compiler\Lexer\NativeStateless;
use Railt\Compiler\Lexer\Stateless;
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
     * @var Stateless
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
     * @return Stateless
     */
    public function getLexer(): Stateless
    {
        if ($this->lexer === null) {
            $this->lexer = new NativeStateless();

            foreach ($this->tokens->getTokens() as $name => $pcre) {
                $this->lexer->add($name, $pcre);
            }

            foreach ($this->tokens->getSkippedTokens() as $name) {
                $this->lexer->skip($name);
            }
        }

        return $this->lexer;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $result = [];

        foreach ($this->getBuilders() as $key => $builder) {
            $result[$key] = $builder->toRule();
        }

        return $result;
    }

    /**
     * @return array|Buildable[]
     */
    public function getBuilders(): iterable
    {
        $analyzer = new RuleAnalyzer($this->getLexer());

        return $analyzer->analyze($this->productions->getRules());
    }
}
