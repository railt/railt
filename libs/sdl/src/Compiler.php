<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Parser\ParserInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Compiler\Command\CompileCommand;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Compiler\Exception\FormatterInterface;
use Railt\SDL\Compiler\Exception\PrettyFormatter;
use Railt\SDL\Compiler\Queue;
use Railt\SDL\Compiler\TypeLoader;
use Railt\SDL\Exception\ParsingException;
use Railt\SDL\Exception\RuntimeExceptionInterface;
use Railt\SDL\Node\Node;
use Railt\SDL\Parser\CachedParser;
use Railt\SDL\Parser\Parser;

final class Compiler implements CompilerInterface
{
    private readonly ParserInterface $parser;
    private readonly TypeLoader $loader;
    private readonly Dictionary $types;
    private readonly FormatterInterface $exceptions;

    public function __construct(
        ?CacheInterface $cache = null,
        DictionaryInterface $types = new Dictionary(),
    ) {
        $this->types = Dictionary::fromDictionary($types);

        $this->parser = $this->bootParser($cache);
        $this->exceptions = $this->bootExceptionFormatter();
        $this->loader = $this->bootTypeLoader();
    }

    private function bootExceptionFormatter(): FormatterInterface
    {
        return new PrettyFormatter();
    }

    private function bootTypeLoader(): TypeLoader
    {
        return new TypeLoader([
            new StandardLibraryLoader(),
        ]);
    }

    private function bootParser(?CacheInterface $cache = null): ParserInterface
    {
        $parser = new Parser();

        if ($cache === null) {
            return $parser;
        }

        return new CachedParser($cache, $parser);
    }

    public function getTypes(): Dictionary
    {
        return $this->types;
    }

    public function addLoader(callable $loader): void
    {
        $this->loader->addLoader($loader);
    }

    public function removeLoader(callable $loader): void
    {
        $this->loader->removeLoader($loader);
    }

    public function getLoaders(): iterable
    {
        return $this->loader->getLoaders();
    }

    private function createContext(Dictionary $types): Context
    {
        return new Context(
            queue: new Queue(),
            dictionary: $types,
            loader: $this->loader,
            process: $this->subprocess(...),
        );
    }

    /**
     * @throws RuntimeExceptionInterface
     */
    private function subprocess(ReadableInterface $source, Context $context): void
    {
        $this->process($source, clone $context);
    }

    /**
     * @throws RuntimeExceptionInterface
     * @throws ParsingException
     */
    private function process(ReadableInterface $source, Context $context): void
    {
        /** @var iterable<Node> $nodes */
        $nodes = $this->parser->parse($source);

        $context->push(new CompileCommand($context, $nodes));

        foreach ($context as $command) {
            $command->exec();
        }
    }

    /**
     * @throws RuntimeExceptionInterface
     */
    private function eval(ReadableInterface $source, Dictionary $types): Dictionary
    {
        try {
            $linker = $this->createContext($types);

            $this->process(File::new($source), $linker);

            return $types;
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeExceptionInterface
     */
    public function load(mixed $source): DictionaryInterface
    {
        return $this->eval(File::new($source), $this->types);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeExceptionInterface
     */
    public function compile(mixed $source): DictionaryInterface
    {
        return $this->eval(File::new($source), clone $this->types);
    }
}
