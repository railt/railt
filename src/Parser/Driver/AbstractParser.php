<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Driver;

use Railt\Io\Readable;
use Railt\Lexer\LexerInterface;
use Railt\Lexer\Token\Unknown;
use Railt\Lexer\TokenInterface;
use Railt\Parser\Ast\Builder;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\Exception\GrammarException;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\GrammarInterface;
use Railt\Parser\ParserInterface;
use Railt\Parser\Rule\Rule;
use Railt\Parser\TokenStream\TokenStream;
use Railt\Parser\Trace\TraceItem;

/**
 * Class AbstractParser
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var LexerInterface
     */
    protected $lexer;

    /**
     * @var GrammarInterface
     */
    protected $grammar;

    /**
     * AbstractParser constructor.
     * @param LexerInterface $lexer
     * @param GrammarInterface $grammar
     */
    public function __construct(LexerInterface $lexer, GrammarInterface $grammar)
    {
        $this->lexer = $lexer;
        $this->grammar = $grammar;
    }

    /**
     * @param Readable $input
     * @return iterable|TraceItem[]
     */
    abstract public function trace(Readable $input): iterable;

    /**
     * @param string $ruleId
     * @param \Closure $then
     * @return AbstractParser|$this
     * @throws GrammarException
     */
    public function extend(string $ruleId, \Closure $then): ParserInterface
    {
        $maxId = \count($this->grammar->getRules()) - 1;

        $result = $then($this->grammar->fetch($ruleId), $maxId);

        if ($result instanceof \Generator) {
            while ($result->valid()) {
                [$key, $value] = [$result->key(), $result->current()];

                switch (true) {
                    case $value instanceof Rule:
                        $this->grammar->addRule($value);
                        $value = $value->getName();
                        break;

                    case \is_string($key) && \is_string($value):
                        if (! \class_exists($value)) {
                            throw new GrammarException('Delegate class ' . $value . '::class not found');
                        }

                        $this->grammar->addDelegate($key, $value);
                        break;

                    default:
                        throw new GrammarException('Bad parser extension generator arguments');
                }

                $result->send($value);
            }
        }

        return $this;
    }

    /**
     * @return GrammarInterface
     */
    public function getGrammar(): GrammarInterface
    {
        return $this->grammar;
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return $this->lexer;
    }

    /**
     * @param Readable $input
     * @return RuleInterface
     * @throws \Railt\Parser\Exception\InternalException
     * @throws \LogicException
     */
    public function parse(Readable $input): RuleInterface
    {
        $trace = $this->trace($input);

        $builder = new Builder($trace, $this->grammar);

        return $builder->build();
    }

    /**
     * @param Readable $input
     * @param int $size
     * @return TokenStream
     * @throws \Railt\Io\Exception\ExternalFileException
     */
    protected function stream(Readable $input, int $size = \PHP_INT_MAX): TokenStream
    {
        return new TokenStream($this->lex($input), $size);
    }

    /**
     * @param Readable $input
     * @return iterable|TokenInterface[]
     * @throws UnexpectedTokenException
     */
    private function lex(Readable $input): iterable
    {
        foreach ($this->lexer->lex($input) as $token) {
            if ($token->getName() === Unknown::T_NAME) {
                $exception = new UnexpectedTokenException(\sprintf('Unexpected token %s', $token));
                $exception->throwsIn($input, $token->getOffset());

                throw $exception;
            }

            yield $token;
        }
    }
}
