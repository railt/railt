<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Console;

use Railt\Compiler\Compiler;
use Railt\Io\File;
use Railt\SDL\Parser\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SDLCompileCommand
 */
class SDLGrammarCompileCommand extends Command
{
    /**
     * @var string
     */
    private const SCHEMA_SDL_GRAMMAR = Parser::GRAMMAR_PATHNAME;

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sdl:grammar:compile');
        $this->setDescription('Compile GraphQL SDL Parser');
    }

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @throws \Railt\Io\Exception\ExternalFileException
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \Throwable
     */
    public function execute(InputInterface $in, OutputInterface $out): void
    {
        $out->write('Compilation: ');

        Compiler::load(File::fromPathname(self::SCHEMA_SDL_GRAMMAR))
            ->setClassName('BaseParser')
            ->setNamespace('Railt\\SDL\\Parser')
            ->saveTo(__DIR__ . '/../Parser');

        $out->writeln('<info>OK</info>');
    }
}
