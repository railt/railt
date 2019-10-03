<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Railt\Parser\Runtime\Lexer;
use Railt\Parser\Runtime\Parser;
use Railt\Parser\Runtime\Builder;
use Phplrt\Contracts\Parser\ParserInterface;
use Railt\Parser\Extension\ExtensionInterface;
use Railt\Parser\Extension\ExtendableInterface;
use Railt\Parser\Exception\SyntaxErrorException;

/**
 * Class Factory
 */
final class Factory implements ParserInterface, ExtendableInterface
{
    /**
     * @var int
     */
    public const PREFER_AUTO = 0x00;

    /**
     * @var int
     */
    public const PREFER_TYPE_SYSTEM = 0x01;

    /**
     * @var int
     */
    public const PREFER_EXECUTABLE = 0x02;

    /**
     * @var string[]
     */
    private const INIT_MAPPINGS = [
        self::PREFER_AUTO        => 'Document',
        self::PREFER_TYPE_SYSTEM => 'TypeSystemLanguage',
        self::PREFER_EXECUTABLE  => 'ExecutableLanguage',
    ];

    /**
     * @var Lexer
     */
    private Lexer $lexer;

    /**
     * @var Builder
     */
    private Builder $builder;

    /**
     * @var Parser
     */
    private Parser $parser;

    /**
     * GraphQLParser constructor.
     *
     * @param int $mode
     * @throws \Throwable
     */
    public function __construct(int $mode = self::PREFER_AUTO)
    {
        $this->lexer = new Lexer();
        $this->builder = new Builder();
        $this->parser = new Parser($this->lexer, $this->builder, $this->getMode($mode));
    }

    /**
     * @return static
     * @throws \Throwable
     */
    public static function graphql(): self
    {
        return new static(self::PREFER_EXECUTABLE);
    }

    /**
     * @return static
     * @throws \Throwable
     */
    public static function sdl(): self
    {
        return new static(self::PREFER_TYPE_SYSTEM);
    }

    /**
     * @param int $mode
     * @return string
     */
    private function getMode(int $mode): string
    {
        return self::INIT_MAPPINGS[$mode] ?? self::INIT_MAPPINGS[self::PREFER_AUTO];
    }

    /**
     * {@inheritDoc}
     */
    public function extend(ExtensionInterface $extension): void
    {
        $this->lexer->extend($extension);
        $this->builder->extend($extension);
        $this->parser->extend($extension);
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable|SyntaxErrorException
     */
    public function parse($source): iterable
    {
        return $this->parser->parse($source);
    }
}

