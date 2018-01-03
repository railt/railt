<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Console;

use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Parser\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CompilerRebuildCommand
 */
class CompilerRebuildCommand extends Command
{
    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compiler:build');
        $this->setDescription('Builds a new optimised compiler from *.pp grammar file.');
    }

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @return int|null|void
     * @throws \LogicException
     */
    public function execute(InputInterface $in, OutputInterface $out)
    {
        $parser = new Factory(new CallStack());
        $parser->compile();

        $out->writeln('<info>OK</info>');
    }
}
