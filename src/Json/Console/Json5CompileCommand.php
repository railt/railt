<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Console;

use Railt\Compiler\Compiler;
use Railt\Io\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Json5CompileCommand
 */
class Json5CompileCommand extends Command
{
    /**
     * @var string
     */
    private const JSON5_GRAMMAR = __DIR__ . '/../../../resources/json5/grammar.pp2';

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compile:json5');
        $this->setDescription('Compile JSON5 Parser');
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

        Compiler::load(File::fromPathname(self::JSON5_GRAMMAR))
            ->setClassName('BaseParser')
            ->setNamespace('Railt\\Json\\Json5')
            ->saveTo(__DIR__ . '/../Json5');

        $out->writeln('<info>OK</info>');
    }
}
