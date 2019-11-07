<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Console;

use Railt\SDL\Parser;
use Phplrt\Source\File;
use Railt\SDL\Ast\Node;
use Railt\SDL\Exception\SyntaxErrorException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Class ParseCommand
 */
class ParseCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'sdl:parse';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'The command to analyze and display the AST structure of the GraphQL source code';
    }

    /**
     * @return void
     */
    public function configure(): void
    {
        $this->addArgument('source', InputArgument::OPTIONAL,
            'GraphQL source file pathname');
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if ($source = $input->getArgument('source')) {
            $source = File::fromPathname($source);

            $output->writeln('Parsing <comment>' . $source->getPathname() . '</comment>' . "\n");

            /** @var Node[] $result */
            $result = (new Parser())->parse($source);

            foreach ($result as $item) {
                $output->writeln(\str_repeat('-', 80));
                $output->writeln(\vsprintf('    File <info>%s:%d</info>', [
                    $item->loc->source->getPathname(),
                    $item->loc->getStartLine(),
                ]));
                $output->writeln(\str_repeat('-', 80));

                $output->writeln((string)$item);
            }

            $output->writeln(\str_repeat('-', 80));

            return;
        }

        $this->interactive($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws RuntimeException
     * @throws \Throwable
     */
    private function interactive(InputInterface $input, OutputInterface $output): void
    {
        $parser = new Parser();
        $question = new Question($this->line('↳'));
        $stream = $this->getHelper('question');

        while (true) {
            $source = $stream->ask($input, $output, $question);

            if ($source === null) {
                continue;
            }

            try {
                $result = \implode("\n", [...$parser->parse($source)]);

                $output->writeln($this->line('⇲', $result, 'yellow'));
            } catch (SyntaxErrorException $e) {
                $message = \vsprintf('<error> %s on line %d at column %d </error>', [
                    $e->getMessage(),
                    $e->getPosition()->getLine(),
                    $e->getPosition()->getColumn()
                ]);

                $output->writeln($this->line('⨯', $message, 'red'));
            }
        }
    }

    /**
     * @param string $msg
     * @param string $body
     * @param string $color
     * @return string
     */
    private function line(string $msg, string $body = '', string $color = 'cyan'): string
    {
        $lines = \explode("\n", \str_replace("\r", '', $body));

        $lines = \array_map(static function (string $line, int $i) use ($color, $msg): string {
            return \sprintf('<bg=%s> %s </> ', $color, $i ? ' ' : $msg) . $line;
        }, $lines, \array_keys($lines));


        return \implode("\n", $lines);
    }
}
