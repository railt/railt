<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json\Console;

use Railt\Component\Io\File;
use Railt\Component\Compiler\Compiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Railt\Component\Io\Exception\NotReadableException;
use Railt\Component\Exception\ExternalException;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * Class Json5CompileCommand
 */
class Json5CompileCommand extends Command
{
    /**
     * @var string
     */
    private const JSON5_GRAMMAR = __DIR__ . '/../Resources/json5/grammar.pp2';

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @throws ExternalException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function execute(InputInterface $in, OutputInterface $out): void
    {
        $out->write('Compilation: ');

        Compiler::load(File::fromPathname(self::JSON5_GRAMMAR))
            ->setClassName('BaseParser')
            ->setNamespace('Railt\\Component\\Json\\Json5\\Decoder')
            ->saveTo(__DIR__ . '/../Json5/Decoder');

        $out->writeln('<info>OK</info>');
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compile:json5');
        $this->setDescription('Compile JSON5 Parser');
    }
}
