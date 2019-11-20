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
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Railt\SDL\Executor\Context;
use Railt\SDL\Parser\Generator;
use Psr\SimpleCache\CacheInterface;
use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;

/**
 * Class Compiler
 */
final class Compiler implements CompilerInterface
{
    use LoggerAwareTrait;

    /**
     * @var int
     */
    public const SPEC_RAW = 0x00;

    /**
     * @var int
     */
    public const SPEC_JUNE_2018 = 0x02;

    /**
     * @var int
     */
    public const SPEC_RAILT = self::SPEC_JUNE_2018 | 0x04;

    /**
     * @var int
     */
    public const SPEC_INTROSPECTION = self::SPEC_JUNE_2018 | 0x08;

    /**
     * @var string[]
     */
    private const SPEC_MAPPINGS = [
        0x02 => __DIR__ . '/../resources/stdlib/stdlib.graphql',
        0x04 => __DIR__ . '/../resources/stdlib/extra.graphql',
        0x08 => __DIR__ . '/../resources/stdlib/introspection.graphql',
    ];

    /**
     * @var ParserInterface
     */
    private ParserInterface $parser;

    /**
     * @var Document
     */
    private Document $document;

    /**
     * @var array|callable[]
     */
    private array $loaders = [];

    /**
     * Compiler constructor.
     *
     * @param int $spec
     * @param CacheInterface|null $cache
     * @param LoggerInterface|null $logger
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function __construct(
        int $spec = self::SPEC_RAILT,
        CacheInterface $cache = null,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger;
        $this->document = new Document();
        $this->parser = new Parser($cache);

        $this->loadSpec($spec);
    }

    /**
     * @param int $spec
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    private function loadSpec(int $spec): void
    {
        foreach (self::SPEC_MAPPINGS as $code => $file) {
            if (($spec & $code) === $code) {
                $this->preload(File::fromPathname($file));
            }
        }
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function preload($source): self
    {
        $context = new Context($this, $this->parser, $this->document, $this->logger);
        $context->compile($source);

        return $this;
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function compile($source): DocumentInterface
    {
        $context = new Context($this, $this->parser, clone $this->document, $this->logger);

        return $context->compile($source);
    }

    /**
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function rebuild(): void
    {
        (new Generator())->generateAndSave();
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaders(): iterable
    {
        return $this->loaders;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function autoload(callable $loader): self
    {
        $this->loaders[] = $loader;

        return $this;
    }

    /**
     * @param callable $loader
     * @return $this
     */
    public function cancelAutoload(callable $loader): self
    {
        $this->loaders = \array_filter($this->loaders, static function (callable $haystack) use ($loader): bool {
            return $haystack !== $loader;
        });

        return $this;
    }
}
