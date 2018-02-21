<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar;

use Railt\Compiler\Generator\Grammar\Exceptions\GrammarException;
use Railt\Compiler\Generator\Grammar\Reader\ConfigureState;
use Railt\Compiler\Generator\Grammar\Reader\IncludeState;
use Railt\Compiler\Generator\Grammar\Reader\LexingState;
use Railt\Compiler\Generator\Grammar\Reader\ParsingState;
use Railt\Compiler\Generator\Pragma;
use Railt\Io\Readable;
use Railt\Compiler\Lexer\Tokens\Output;

/**
 * Class Reader
 */
class Reader implements GrammarDefinition
{
    /**@#+
     * Current states list
     */
    private const STATE_CONFIGURE = 0x00;
    private const STATE_LEXING    = 0x01;
    private const STATE_PARSING   = 0x02;
    private const STATE_INCLUDE   = 0x04;
    /**@#-*/

    /**
     * When during parsing we find the specific name of the
     *  token - we switch the state of the machine to another
     *  way of processing the data.
     *
     * We find:
     *  1) Token "%pragma"               -> STATE_CONFIGURE
     *  2) Token "%include"              -> STATE_INCLUDE
     *  3) Token "%token"                -> STATE_LEXING
     *  5) Token "RuleDefinition: ..."   -> STATE_PARSING
     */
    private const STATE_JUMPS = [
        Lexer::T_PRAGMA          => self::STATE_CONFIGURE,
        Lexer::T_INCLUDE         => self::STATE_INCLUDE,
        Lexer::T_TOKEN           => self::STATE_LEXING,
        Lexer::T_NODE_DEFINITION => self::STATE_PARSING,
    ];

    /**
     * Each of the tokens has its own execution context.
     *  In the event that one of the tokens is not in the
     *  context - we define this moment and can generate
     *  an error.
     */
    private const STATE_CONTEXT = [
        self::STATE_CONFIGURE => [
            Lexer::T_PRAGMA,
        ],
        self::STATE_INCLUDE   => [
            Lexer::T_INCLUDE,
        ],
        self::STATE_LEXING    => [
            Lexer::T_TOKEN,
        ],
        self::STATE_PARSING   => [
            Lexer::T_NODE_DEFINITION,
            Lexer::T_OR,
            Lexer::T_ZERO_OR_ONE,
            Lexer::T_ONE_OR_MORE,
            Lexer::T_ZERO_OR_MORE,
            Lexer::T_N_TO_M,
            Lexer::T_ZERO_TO_M,
            Lexer::T_N_OR_MORE,
            Lexer::T_EXACTLY_N,
            Lexer::T_SKIPPED,
            Lexer::T_KEPT,
            Lexer::T_NAMED,
            Lexer::T_NODE,
            Lexer::T_GROUP_OPEN,
            Lexer::T_GROUP_CLOSE,
        ],
    ];

    /**
     * List of descriptions of grammar reading states. It is
     *  necessary in order to display error messages
     *  correctly and beautifully.
     */
    private const STATE_DESCRIPTIONS = [
        self::STATE_INCLUDE   => 'including (Grammar injection)',
        self::STATE_CONFIGURE => 'configuring (Bootstrap)',
        self::STATE_LEXING    => 'lexing (Read tokens list)',
        self::STATE_PARSING   => 'parsing (Read rules list)',
    ];

    /**
     * @var int
     */
    private $state = self::STATE_CONFIGURE;

    /**
     * @var array|LexingState
     */
    private $tokens;

    /**
     * @var array|ConfigureState
     */
    private $pragma;

    /**
     * @var array|ParsingState
     */
    private $rules;

    /**
     * @var array|IncludeState
     */
    private $includes;

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * Reader constructor.
     * @param Readable $grammar
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    public function __construct(Readable $grammar)
    {
        $this->lexer = new Lexer();

        $this->includes = new IncludeState();
        $this->pragma   = new ConfigureState();
        $this->tokens   = new LexingState();
        $this->rules    = new ParsingState($this->tokens);

        $this->read($grammar);
    }

    /**
     * @param Readable $grammar
     * @return void
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    private function read(Readable $grammar): void
    {
        $stream = $this->lexer->read($grammar)->channel(Lexer::CHANNEL_TOKENS)->get();

        foreach ($stream as $token) {
            $this->checkIncludes();

            if (\array_key_exists($token[Output::I_TOKEN_NAME], self::STATE_JUMPS)) {
                $this->state = self::STATE_JUMPS[$token[Output::I_TOKEN_NAME]];
            }

            $this->verifyTokenState($grammar, $token);

            $this->parse($grammar, $token);
        }

        $this->checkIncludes();
    }

    /**
     * @return void
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    private function checkIncludes(): void
    {
        if (! $this->includes->isEmpty()) {
            $file = $this->includes->pop();
            $this->read($file);
        }
    }

    /**
     * @param Readable $grammar
     * @param array $token
     * @return array
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    private function verifyTokenState(Readable $grammar, array $token): array
    {
        $allowed = self::STATE_CONTEXT[$this->state];

        if (! \in_array($token[Output::I_TOKEN_NAME], $allowed, true)) {
            throw GrammarException::fromFile(
                $this->invalidTokenStateMessage($token),
                $grammar,
                $grammar->getPosition($token[Output::I_TOKEN_OFFSET])
            );
        }

        return $token;
    }

    /**
     * @param array $token
     * @return string
     */
    private function invalidTokenStateMessage(array $token): string
    {
        $message = 'The "%s" (%s) is not available for use during the state of %s grammar analysis';
        $body    = \trim(\str_replace(["\r", "\n"], '', $token[Output::I_TOKEN_BODY]));

        return \vsprintf($message, [
            $body,
            Lexer::getTokenName($token[Output::I_TOKEN_NAME]),
            self::STATE_DESCRIPTIONS[$this->state],
        ]);
    }

    /**
     * @param Readable $grammar
     * @param array $token
     * @return void
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    private function parse(Readable $grammar, array $token): void
    {
        switch ($this->state) {
            case self::STATE_CONFIGURE:
                $this->pragma->resolve($grammar, $token);
                break;

            case self::STATE_LEXING:
                $this->tokens->resolve($grammar, $token);
                break;

            case self::STATE_INCLUDE:
                $this->includes->resolve($grammar, $token);
                $this->state = self::STATE_PARSING;
                break;

            case self::STATE_PARSING:
                $this->rules->resolve($grammar, $token);
                break;
        }
    }

    /**
     * @return iterable|array[]
     */
    public function getTokenDefinitions(): iterable
    {
        return $this->tokens->getData();
    }

    /**
     * @return iterable|Pragma
     */
    public function getPragmaDefinitions(): Pragma
    {
        return $this->pragma->getData();
    }

    /**
     * @return iterable|array[]
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\GrammarException
     */
    public function getRuleDefinitions(): iterable
    {
        return $this->rules->getData();
    }
}
