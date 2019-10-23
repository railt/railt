<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Console;

use Railt\Parser\Generator\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        return 'parser:parse';
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
        $this->addArgument('file', InputArgument::REQUIRED,
            'GraphQL file pathname');
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<comment>Generating</comment>');

        $generator = new Generator();
        $generator->generateBuilder();

        $output->writeln('<info>Generated</info>');
    }
}
