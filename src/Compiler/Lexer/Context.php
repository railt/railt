<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Io\Position;
use Railt\Io\Readable;
use Railt\Compiler\Lexer\Exceptions\LexerException;
use Railt\Compiler\Lexer\Tokens\Channel;
use Railt\Compiler\Lexer\Tokens\Eof;
use Railt\Compiler\Lexer\Tokens\Output;

/**
 * Class Context
 */
class Context implements \IteratorAggregate
{
    public const INPUT_INDEX_PATTERN = 0x00;
    public const INPUT_INDEX_CHANNEL = 0x01;

    /**
     * Regex boundary
     */
    private const REGEX_BOUNDARY = '#';

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var Readable
     */
    private $input;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var int
     */
    private $bytesOffset = 0;

    /**
     * @var int
     */
    private $charsOffset = 0;

    /**
     * @var string
     */
    private $content;

    /**
     * @var array|\SplStack
     */
    private $result = [];

    /**
     * @var array
     */
    private $tokenMappings = [];

    /**
     * @var array
     */
    private $channels = [];

    /**
     * Context constructor.
     * @param iterable $tokens
     * @param Configuration $config
     * @param Readable $input
     */
    public function __construct(iterable $tokens, Configuration $config, Readable $input)
    {
        $this->input   = $input;
        $this->content = $input->getContents();
        $this->config  = $config;
        $this->pattern = $this->regex($tokens);
    }

    /**
     * @param iterable $tokens
     * @return string
     */
    private function regex(iterable $tokens): string
    {
        $pattern = \implode('|', $this->tokensToPattern($tokens));

        return $this->pattern($pattern, $this->flags());
    }

    /**
     * @param iterable $tokens
     * @return array
     */
    private function tokensToPattern(iterable $tokens): array
    {
        $result = [];

        foreach ($tokens as $name => $body) {
            $pattern     = ((array)$body)[self::INPUT_INDEX_PATTERN];
            $patternName = \preg_quote($this->createTokenName($name), self::REGEX_BOUNDARY);
            $patternBody = \str_replace(self::REGEX_BOUNDARY, '\\' . self::REGEX_BOUNDARY, $pattern);

            $result[] = \sprintf('(?<%s>%s)', \trim($patternName), $patternBody);

            /**
             * This piece of code is badly organized, but it is required for speed optimization.
             * TODO It is necessary in the future to spread these methods into several pieces.
             */
            $channel = ((array)$body)[self::INPUT_INDEX_CHANNEL] ?? null;

            if ($channel !== null) {
                $this->channels[$name] = $body[self::INPUT_INDEX_CHANNEL];
            }
        }

        return $result;
    }

    /**
     * @param mixed $name
     * @return string
     */
    private function createTokenName($name): string
    {
        static $tokenId = 0;

        $identifier = 'T' . $tokenId++;

        $this->tokenMappings[$identifier] = $name;

        return $identifier;
    }

    /**
     * @param string $body
     * @param string $flags
     * @return string
     */
    private function pattern(string $body, string $flags): string
    {
        return \sprintf('#(%s)#%s', $body, $flags);
    }

    /**
     * @return string
     */
    private function flags(): string
    {
        $flags = '';

        if ($this->config->modeMultiline) {
            $flags .= 'm';
        }

        if ($this->config->modeIsUnicode) {
            $flags .= 'u';
        }

        if ($this->config->modeDotAll) {
            $flags .= 's';
        }

        return $flags;
    }

    /**
     * @return \Traversable|array[]
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    public function getIterator(): \Traversable
    {
        \preg_replace_callback($this->pattern, function (array $matches): void {
            [$name, $body, $context] = $this->getTokenInfo($matches);
            $length = \strlen($body);

            if ($this->config->verifyUnrecognizedTokens) {
                $this->checkOffset($body);
            }

            $realTokenName = $this->tokenMappings[$name];
            $channel       = $this->channels[$realTokenName] ?? Channel::DEFAULT;

            $this->result[] = [
                Output::I_TOKEN_NAME    => $realTokenName,
                Output::I_TOKEN_BODY    => $body,
                Output::I_TOKEN_LENGTH  => $length,
                Output::I_TOKEN_OFFSET  => $this->bytesOffset,
                Output::I_TOKEN_CONTEXT => $context,
                Output::I_TOKEN_CHANNEL => $channel,
            ];

            $this->bytesOffset += $length;
            $this->charsOffset += \mb_strlen($body);
        }, $this->content);


        if ($this->config->verifyUnrecognizedTokens && $this->bytesOffset !== \strlen($this->content)) {
            $this->throwUnrecognizedToken();
        }

        yield from $this->result;

        if ($this->config->addEndOfFileToken) {
            yield Eof::create($this->bytesOffset);
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    private function getTokenInfo(array $data): array
    {
        $last    = '';
        $context = [];

        foreach (\array_reverse($data) as $index => $body) {
            if (! \is_string($index)) {
                $context[] = $body;
                continue;
            }

            $last = $index;

            if ($body !== '') {
                return [$index, $body, \array_slice(\array_reverse($context), 1)];
            }
        }

        return $this->throwEmptyLexeme($last);
    }

    /**
     * @param string $lastIndex
     * @return array
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    private function throwEmptyLexeme(string $lastIndex): array
    {
        $error = 'A lexeme must not match an empty value, which is the case of "%s"';
        throw new LexerException(\sprintf($error, $lastIndex));
    }

    /**
     * @param string $body
     * @return void
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    private function checkOffset(string $body): void
    {
        if (\substr($this->content, $this->bytesOffset, \strlen($body)) !== $body) {
            $this->throwUnrecognizedToken();
        }
    }

    /**
     * @return void
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    protected function throwUnrecognizedToken(): void
    {
        $class       = $this->config->unrecognizedTokenException;
        $position    = $this->input->getPosition($this->bytesOffset);
        $errorAtChar = $this->inline(\mb_substr($this->content, $this->charsOffset, 1));

        $error = \vsprintf('Unrecognized "%s" on line %d at column %d', [
            $errorAtChar,
            $position->getLine(),
            $position->getColumn() + 1,
        ]);

        throw $this->throwLexerException($class, $error, $position);
    }

    /**
     * @param string $text
     * @return string
     */
    private function inline(string $text): string
    {
        return \str_replace(["\n", "\r"], '', $text);
    }

    /**
     * @param string $class
     * @param string $message
     * @param Position $position
     * @return LexerException
     * @throws \Railt\Compiler\Lexer\Exceptions\LexerException
     */
    private function throwLexerException(string $class, string $message, Position $position): LexerException
    {
        if (\is_subclass_of(LexerException::class, $class)) {
            $error = 'A lexer throwable class must be an instance of %s, but %s given';
            throw new LexerException(\sprintf($error, LexerException::class, $class));
        }

        /** @var LexerException $instance */
        $instance = new $class($message);
        $instance->inFile($this->input, $position);

        return $instance;
    }
}
