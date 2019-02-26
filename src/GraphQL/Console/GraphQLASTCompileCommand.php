<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Console;

use Railt\Compiler\Compiler;
use Railt\Io\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GraphQLASTCompileCommand
 */
class GraphQLASTCompileCommand extends Command
{
    /**
     * @var string
     */
    private const SCHEMA_SDL_GRAMMAR = __DIR__ . '/../../../resources/graphql/grammar.pp2';

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @throws \Throwable
     */
    public function execute(InputInterface $in, OutputInterface $out): void
    {
        if (! \class_exists(Compiler::class)) {
            $message = 'Package railt/compiler is required to compile the grammar source code';
            $out->writeln(\sprintf('<error>%s</error>', $message));

            return;
        }

        $out->writeln(\sprintf('<comment>Grammar found at %s</comment>',
            \realpath(self::SCHEMA_SDL_GRAMMAR) ?: self::SCHEMA_SDL_GRAMMAR));

        $compiler = $this->process($out, 'Reading grammar', function () {
            return Compiler::load(File::fromPathname(self::SCHEMA_SDL_GRAMMAR));
        });

        $this->process($out, 'Compilation', function () use ($compiler): void {
            $compiler
                ->setClassName('BaseParser')
                ->setNamespace('Railt\\GraphQL\\Frontend')
                ->saveTo(__DIR__ . '/../Frontend');
        });
    }

    /**
     * @param OutputInterface $output
     * @param string $title
     * @param \Closure $command
     * @return mixed
     * @throws \Throwable
     */
    private function process(OutputInterface $output, string $title, \Closure $command)
    {
        $output->write('<comment>' . $title . ':</comment> ');

        try {
            $result = $command();
        } catch (\Throwable $e) {
            $output->writeln('<error>Failed</error>');
            throw $e;
        }

        $output->writeln('<info>OK</info>');

        return $result;
    }

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function configure(): void
    {
        $this->setName('compile:graphql');
        $this->setDescription('Compiles the initial GraphQL grammar into the PHP source code');
    }
}
