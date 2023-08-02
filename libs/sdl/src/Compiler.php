<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Compiler\Command\CompileCommand;
use Railt\SDL\Compiler\Command\Evaluate\GenerateSchemaCommand;
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
use Railt\SDL\Parser\ParserInterface;
use Railt\TypeSystem\DictionaryInterface;

final class Compiler implements CompilerInterface
{
    private Dictionary $types;

    private readonly ParserInterface $parser;
    private readonly TypeLoader $loader;
    private readonly FormatterInterface $exceptions;

    public function __construct(
        public readonly Config $config = new Config(),
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
            new StandardLibraryLoader(
                config: $this->config,
            ),
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

    /**
     * @param array<non-empty-string, mixed> $variables
     */
    private function createContext(Dictionary $types, array $variables): Context
    {
        return new Context(
            variables: $variables,
            queue: new Queue(),
            types: $types,
            config: $this->config,
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

        $context->exec(new CompileCommand($context, $nodes));

        foreach ($context as $command) {
            $context->exec($command);
        }
    }

    /**
     * @throws RuntimeExceptionInterface
     */
    private function eval(ReadableInterface $source, Context $context): Dictionary
    {
        try {
            $this->process(File::new($source), $context);

            return $context->types;
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeExceptionInterface
     */
    public function load(mixed $source, array $variables = []): DictionaryInterface
    {
        $source = File::new($source);

        $context = $this->createContext(clone $this->types, $variables);

        return $this->eval($source, $context);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeExceptionInterface
     */
    public function compile(mixed $source, array $variables = []): DictionaryInterface
    {
        $source = File::new($source);

        $context = $this->createContext(clone $this->types, $variables);

        $result = $this->eval($source, $context);

        try {
            $context->exec(new GenerateSchemaCommand($context));
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }

        return $result;
    }

    public function __clone()
    {
        $this->types = clone $this->types;
    }
}
