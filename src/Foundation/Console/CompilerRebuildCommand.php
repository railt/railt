<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console;

use Railt\Compiler\Generator\LexerGenerator;
use Railt\Compiler\Generator\ParserGenerator;
use Railt\Compiler\Grammar\ParsingResult;
use Railt\Compiler\Grammar\Reader;
use Railt\Io\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CompilerRebuildCommand
 */
class CompilerRebuildCommand extends Command
{
    private const DEFAULT_GRAMMAR_FILE      = __DIR__ . '/../../SDL/resources/grammar/sdl.pp2';
    private const DEFAULT_PATH              = __DIR__ . '/../../SDL/Parser';
    private const DEFAULT_LEXER_CLASS_NAME  = '\\Railt\\SDL\\Parser\\SchemaLexer';
    private const DEFAULT_PARSER_CLASS_NAME = '\\Railt\\SDL\\Parser\\SchemaParser';

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @return int|null|void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function execute(InputInterface $in, OutputInterface $out)
    {
        $grammar = File::fromPathname($in->getArgument('grammar'));
        $reader  = (new Reader())->read($grammar);

        $this->buildLexer($in, $reader);
        $this->buildParser($in, $reader);

        $out->writeln('<info>OK</info>');
    }

    /**
     * @param InputInterface $in
     * @param ParsingResult $reader
     * @throws \RuntimeException
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function buildLexer(InputInterface $in, ParsingResult $reader): void
    {
        [$class, $namespace] = $this->split($in->getArgument('lexer'));

        $lexer = new LexerGenerator($reader->getLexer());
        $lexer->class($class);
        $lexer->namespace($namespace);
        $lexer->build()->saveTo($in->getArgument('output'));
    }

    /**
     * @param string $class
     * @return array
     */
    private function split(string $class): array
    {
        $parts = \array_filter(\explode('\\', $class));

        return [\array_pop($parts), \implode('\\', $parts)];
    }

    /**
     * @param InputInterface $in
     * @param ParsingResult $reader
     * @throws \RuntimeException
     * @throws \Railt\Io\Exceptions\NotReadableException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function buildParser(InputInterface $in, ParsingResult $reader): void
    {
        [$class, $namespace] = $this->split($in->getArgument('parser'));

        $lexer = new ParserGenerator($reader->getParser(), $in->getArgument('lexer'));
        $lexer->class($class);
        $lexer->namespace($namespace);
        $lexer->build()->saveTo($in->getArgument('output'));
    }

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compiler:build');
        $this->setDescription('Builds a new optimised lexer and parser from .pp/.pp2 grammar file.');

        $this->addArgument(
            'output',
            InputArgument::OPTIONAL,
            'Output generated lexer directory',
            self::DEFAULT_PATH
        );

        $this->addArgument(
            'grammar',
            InputArgument::OPTIONAL,
            'Input grammar file',
            self::DEFAULT_GRAMMAR_FILE
        );

        $this->addArgument(
            'lexer',
            InputArgument::OPTIONAL,
            'The lexer output class name',
            self::DEFAULT_LEXER_CLASS_NAME
        );

        $this->addArgument(
            'parser',
            InputArgument::OPTIONAL,
            'The parser output class name',
            self::DEFAULT_PARSER_CLASS_NAME
        );
    }
}
