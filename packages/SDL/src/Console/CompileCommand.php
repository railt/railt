<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Console;

use Railt\SDL\Frontend\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CompileCommand
 */
class CompileCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'sdl:compile';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'A command to generate GraphQL lexer and parser by its (E)BNF-like grammar';
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Generating</comment>');

        $generator = new Generator();
        $generator->generateAndSave();

        $out = \dirname(__DIR__) . '/Parser/Factory.php';

        $output->writeln('<info>Successfully generated in ' . $out . '</info>');

        return 0;
    }
}
