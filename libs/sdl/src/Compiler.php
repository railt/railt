<?php

declare(strict_types=1);

namespace Railt\SDL;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;
use Railt\SDL\Compiler\Command\CompileCommand;
use Railt\SDL\Compiler\Context;
use Railt\SDL\Compiler\Exception\FormatterInterface;
use Railt\SDL\Compiler\Exception\PrettyFormatter;
use Railt\SDL\Compiler\Queue;
use Railt\SDL\Compiler\TypeLoader;
use Railt\SDL\Exception\RuntimeExceptionInterface;
use Railt\SDL\Node\Statement\Statement;

final class Compiler implements CompilerInterface
{
    private readonly Parser $parser;
    private readonly TypeLoader $loader;
    private readonly Dictionary $types;

    public function __construct(
        bool $bootStandardLibrary = true,
        DictionaryInterface $types = new Dictionary(),
        private readonly FormatterInterface $exceptions = new PrettyFormatter(),
    ) {
        $this->parser = new Parser();
        $this->loader = new TypeLoader();
        $this->types = Dictionary::fromDictionary($types);

        if ($bootStandardLibrary) {
            /** @psalm-suppress PossiblyInvalidArgument : impure-callable to callable cast */
            $this->loader->addLoader(new StandardLibraryLoader());
        }
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
     */
    private function process(ReadableInterface $source, Context $context): void
    {
        /** @var iterable<Statement> $statements */
        $statements = $this->parser->parse($source);

        $context->push(new CompileCommand($context, $statements));

        foreach ($context as $command) {
            $command->exec();
        }
    }

    public function load(mixed $source): DictionaryInterface
    {
        try {
            $context = $this->createContext($this->types);

            $this->process(File::new($source), $context);

            return $this->types;
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }
    }

    public function compile(mixed $source): DictionaryInterface
    {
        try {
            $linker = $this->createContext($result = clone $this->types);

            $this->process(File::new($source), $linker);

            return $result;
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }
    }
}
