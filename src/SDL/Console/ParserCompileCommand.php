<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Console;

use Railt\Compiler\Generator\ParserGenerator;
use Railt\Compiler\Grammar\ParsingResult;
use Railt\Compiler\Grammar\Reader;
use Railt\Io\File;
use Railt\SDL\Parser\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ParserCompileCommand
 */
class ParserCompileCommand extends Command
{
    /**
     * @var string Default parser output path
     */
    private const DEFAULT_PATH = __DIR__ . '/../Parser';

    /**
     * @var string Default generated parser FQN
     */
    private const DEFAULT_PARSER_CLASS_NAME = \Railt\SDL\Parser\SchemaParser::class;

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @return int|null|void
     * @throws \LogicException
     */
    public function execute(InputInterface $in, OutputInterface $out)
    {
        $grammar = File::fromPathname($in->getArgument('grammar'));

        $this->buildParser($in, (new Reader())->read($grammar));
        $out->writeln('<info>OK</info>');
    }

    /**
     * @param InputInterface $in
     * @param ParsingResult $reader
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function buildParser(InputInterface $in, ParsingResult $reader): void
    {
        [$class, $namespace] = $this->split($in->getArgument('parser'));

        (new ParserGenerator($reader))
            ->class($class)->namespace($namespace)->build()
            ->saveTo($in->getArgument('output'));
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
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    protected function configure(): void
    {
        $this->setName('parser:compile');
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
            Factory::GRAMMAR_FILE
        );

        $this->addArgument(
            'parser',
            InputArgument::OPTIONAL,
            'The parser output class name',
            self::DEFAULT_PARSER_CLASS_NAME
        );
    }
}
