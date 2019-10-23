<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Source\File;
use Railt\Parser\Factory;
use Railt\SDL\Executor\Executor;
use Railt\SDL\Document\Document;
use Railt\SDL\Document\Decorator;
use Railt\SDL\Linker\LinkerInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Railt\SDL\Document\MutableDocument;
use Phplrt\Source\Exception\NotFoundException;
use Railt\Parser\Exception\SyntaxErrorException;
use Phplrt\Source\Exception\NotReadableException;
use Railt\Contracts\TypeSystem\CompilerInterface;
use Railt\Contracts\TypeSystem\DocumentInterface;
use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;

/**
 * Class Compiler
 */
class Compiler implements CompilerInterface
{
    /**
     * @var int
     */
    public const MODE_EMPTY = 0x00;

    /**
     * @var int
     */
    public const MODE_STANDARD = 0x02;

    /**
     * @var int
     */
    public const MODE_INTROSPECTION = 0x04;

    /**
     * @var int
     */
    public const MODE_EXTENDED = 0x08;

    /**
     * @var int
     */
    private const FLAGS_EXTRAS = self::MODE_EXTENDED;

    /**
     * @var int
     */
    private const FLAGS_STDLIB = self::MODE_STANDARD | self::MODE_INTROSPECTION | self::MODE_EXTENDED;

    /**
     * @var int
     */
    private const FLAGS_INTROSPECTION = self::MODE_EXTENDED | self::MODE_INTROSPECTION;

    /**
     * @var int[]
     */
    private const LIBRARIES = [
        __DIR__ . '/../resources/stdlib/extras.graphql'        => self::FLAGS_EXTRAS,
        __DIR__ . '/../resources/stdlib/stdlib.graphql'        => self::FLAGS_STDLIB,
        __DIR__ . '/../resources/stdlib/introspection.graphql' => self::FLAGS_INTROSPECTION,
    ];

    /**
     * @var Document
     */
    private Document $document;

    /**
     * @var ParserInterface
     */
    private ParserInterface $parser;

    /**
     * @var Executor
     */
    private Executor $executor;

    /**
     * Compiler constructor.
     *
     * @param int $mode
     * @param ParserInterface|null $parser
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function __construct(int $mode = self::FLAGS_INTROSPECTION, ParserInterface $parser = null)
    {
        $this->parser = $parser ?? Factory::sdl();

        $this->document = new MutableDocument();
        $this->executor = new Executor($this);

        $this->boot($mode);
    }

    /**
     * @param int $mode
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    private function boot(int $mode): void
    {
        foreach (self::LIBRARIES as $library => $opt) {
            if (($opt & $mode) !== 0) {
                $this->preload(File::fromPathname($library));
            }
        }
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function preload($source, array $types = null): void
    {
        $this->executor->execute($this->document, $this->parser->parse($source));
    }

    /**
     * @return DocumentInterface
     */
    public function getDocument(): DocumentInterface
    {
        return $this->document;
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function compile($source, array $types = null): DocumentInterface
    {
        $this->executor->execute($document = new Decorator($this->document), $this->parser->parse($source));

        return $document;
    }

    /**
     * {@inheritDoc}
     */
    public function autoload(LinkerInterface $linker): void
    {
        $this->executor->addLinker($linker);
    }
}
