<?php

declare(strict_types=1);

namespace Railt\SDL;

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

final class Compiler implements CompilerInterface
{
    private readonly Parser $parser;
    private readonly TypeLoader $loader;
    private readonly Dictionary $types;
    private readonly FormatterInterface $exceptions;

    public function __construct(
        private readonly ?CacheInterface $cache = null,
        DictionaryInterface $types = new Dictionary(),
    ) {
        $this->parser = new Parser($this->cache);
        $this->loader = new TypeLoader();
        $this->exceptions = new PrettyFormatter();
        $this->types = Dictionary::fromDictionary($types);

        /** @psalm-suppress PossiblyInvalidArgument : impure-callable to callable cast */
        $this->loader->addLoader(new StandardLibraryLoader());
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
        $statements = $this->parser->parse($source);

        $context->push(new CompileCommand($context, $statements));

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
