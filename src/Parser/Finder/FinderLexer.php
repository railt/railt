<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Finder;

use Railt\Io\Readable;
use Railt\Lexer\Definition\TokenDefinition;
use Railt\Lexer\Factory;
use Railt\Lexer\LexerInterface;
use Railt\Lexer\TokenInterface;

/**
 * Class FinderLexer
 */
class FinderLexer implements LexerInterface
{
    public const T_WHITESPACE   = 'T_WHITESPACE';
    public const T_LEAF         = 'T_LEAF';
    public const T_RULE         = 'T_RULE';
    public const T_NODE         = 'T_NODE';
    public const T_EXACT_DEPTH  = 'T_EXACT_DEPTH';
    public const T_DIRECT_DEPTH = 'T_DIRECT_DEPTH';
    public const T_ANY          = 'T_ANY';

    /**
     * @var string[]
     */
    private const TOKENS = [
        self::T_WHITESPACE   => '\s+',
        self::T_LEAF         => ':(\w+)\b',
        self::T_RULE         => '#(\w+)\b',
        self::T_NODE         => '(\w+)\b',
        self::T_DIRECT_DEPTH => '>',
        self::T_EXACT_DEPTH  => '\(\h*(\d+)\h*\)',
        self::T_ANY          => '\*',
    ];

    /**
     * @var string[]
     */
    private const TOKENS_EXPR = [
        self::T_DIRECT_DEPTH,
        self::T_EXACT_DEPTH,
    ];

    /**
     * @var string[]
     */
    private const TOKENS_SKIP = [
        self::T_WHITESPACE,
    ];

    /**
     * @var LexerInterface
     */
    private $inner;

    /**
     * FinderLexer constructor.
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    public function __construct()
    {
        $this->inner = Factory::create(self::TOKENS, self::TOKENS_SKIP);
    }

    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    public function lookahead(Readable $input): \Traversable
    {
        $previous = null;

        foreach ($this->lex($input) as $token) {
            yield $previous => $token;
            $previous = $token;
        }
    }

    /**
     * @param Readable $input
     * @return \Traversable|TokenInterface[]
     */
    public function lex(Readable $input): \Traversable
    {
        return $this->inner->lex($input);
    }

    /**
     * @return iterable|TokenDefinition[]
     */
    public function getTokenDefinitions(): iterable
    {
        return $this->inner->getTokenDefinitions();
    }

    /**
     * @param null|TokenInterface $token
     * @return bool
     */
    public function isExpression(?TokenInterface $token): bool
    {
        return $token && \in_array($token->getName(), self::TOKENS_EXPR, true);
    }
}
