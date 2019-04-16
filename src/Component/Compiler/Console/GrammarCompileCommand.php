<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Console;

use Railt\Component\Io\File;
use Railt\Component\Compiler\Compiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Railt\Component\Io\Exception\NotReadableException;
use Railt\Component\Exception\ExternalException;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * Class GrammarCompileCommand
 */
final class GrammarCompileCommand extends Command
{
    /**
     * @var string Default parser output path
     */
    private const PARSER_GRAMMAR = __DIR__ . '/../Resources/pp2/grammar.pp2';

    /**
     * @var string Default parser output path
     */
    private const OUT_PATH = __DIR__ . '/../Grammar';

    /**
     * @var string Default generated parser class name
     */
    private const OUT_CLASS_NAME = 'Parser';

    /**
     * @var string Default generated parser class name
     */
    private const OUT_NAMESPACE = 'Railt\\Component\\Compiler\\Grammar';

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @throws ExternalException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function execute(InputInterface $in, OutputInterface $out): void
    {
        $compiler = Compiler::load(File::fromPathname(self::PARSER_GRAMMAR));

        $compiler->setNamespace(self::OUT_NAMESPACE)->setClassName(self::OUT_CLASS_NAME)->saveTo(self::OUT_PATH);
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compile:grammar');
        $this->setDescription('Builds a new grammar parser from .pp/.pp2 grammar file.');
    }
}
