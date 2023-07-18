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

final class Compiler implements CompilerInterface
{
    private readonly Parser $parser;
    private readonly TypeLoader $loader;
    private readonly Dictionary $types;

    public function __construct(
        DictionaryInterface $types = new Dictionary(),
        private readonly FormatterInterface $exceptions = new PrettyFormatter(),
    ) {
        $this->parser = new Parser();
        $this->loader = new TypeLoader();
        $this->types = Dictionary::fromDictionary($types);

        $this->booStandardLibrary();
    }

    private function booStandardLibrary(): void
    {
        $this->addLoader(static function (string $name): ?ReadableInterface {
            if (\is_file($pathname = __DIR__ . '/../resources/stdlib/' . $name . '.graphql')) {
                return File::fromPathname($pathname);
            }

            if (\is_file($pathname = __DIR__ . '/../resources/stdlib/@' . $name . '.graphql')) {
                return File::fromPathname($pathname);
            }

            return null;
        });
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

    private function getLinker(Dictionary $types): Context
    {
        return new Context(
            queue: new Queue(),
            dictionary: $types,
            loader: $this->loader,
            process: $this->innerProcess(...),
        );
    }

    private function innerProcess(ReadableInterface $source, Context $context): void
    {
        $this->process($source, clone $context);
    }

    private function process(ReadableInterface $source, Context $context): void
    {
        $statements = $this->parser->parse($source);

        $context->push(new CompileCommand($context, $statements));

        foreach ($context as $command) {
            $command->exec();
        }
    }

    public function load(mixed $source): DictionaryInterface
    {
        try {
            $linker = $this->getLinker($this->types);

            $this->process(File::new($source), $linker);

            return $this->types;
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }
    }

    public function compile(mixed $source): DictionaryInterface
    {
        try {
            $linker = $this->getLinker($result = clone $this->types);

            $this->process(File::new($source), $linker);

            return $result;
        } catch (RuntimeExceptionInterface $e) {
            throw $this->exceptions->format($e);
        }
    }
}
