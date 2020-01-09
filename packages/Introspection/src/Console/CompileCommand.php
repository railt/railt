<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Console;

use Phplrt\Compiler\Analyzer;
use Phplrt\Compiler\Compiler;
use Phplrt\Source\File;
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
        return 'introspection:parser:compile';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'A command to generate GraphQL Introspection Values lexer and parser by its (E)BNF-like grammar';
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<comment>Generating</comment>');

        $compiler = new Compiler();
        $compiler->load(File::fromPathname(__DIR__ . '/../../resources/values-grammar.pp2'));

        $result = $this->render($compiler->getAnalyzer());

        \file_put_contents(__DIR__ . '/../Parser.php', $result);

        $output->writeln('<info>Introspection parser is successfully generated</info>');
    }

    /**
     * @param Analyzer $ctx
     * @return string
     */
    private function render(Analyzer $ctx): string
    {
        \ob_start();

        require __DIR__ . '/../../resources/template.php';

        return \ob_get_clean();
    }
}
