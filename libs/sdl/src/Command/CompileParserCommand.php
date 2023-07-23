<?php

declare(strict_types=1);

namespace Railt\SDL\Command;

use Phplrt\Compiler\Compiler;
use Phplrt\Source\File;
use Railt\SDL\Parser\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CompileParserCommand extends Command
{
    /**
     * @var non-empty-string
     */
    private const GRAMMAR_INPUT = __DIR__ . '/../../resources/grammar/grammar.pp2';

    /**
     * @var non-empty-string
     */
    private const GRAMMAR_OUTPUT = Parser::DEFAULT_GRAMMAR_PATHNAME;

    /**
     * @var array<array-key, non-empty-string>
     */
    private const NAMESPACE_IMPORTS = [
        '\\Railt\\SDL\\Node',
        '\\Railt\\SDL\\Node\\Expression\\' => 'Expr',
        '\\Railt\\SDL\\Node\\Statement\\' => 'Stmt'
    ];

    public function __construct(string $name = null)
    {
        parent::__construct($name ?? 'railt:sdl:compile');
    }

    protected function configure(): void
    {
        $this->setDescription('Compiles the GraphQL SDL grammar (development only)');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!class_exists(Compiler::class)) {
            throw new \LogicException('The "railt/compiler" dependency required');
        }

        $output->writeln(vsprintf(' - Loading <comment>%s</comment>', [
            realpath(self::GRAMMAR_INPUT) ?: self::GRAMMAR_INPUT,
        ]));

        $compiler = new Compiler();
        $compiler->load(File::fromPathname(self::GRAMMAR_INPUT));

        $output->write(' - Building');

        try {
            $assembly = $compiler->build();

            /** @psalm-suppress ArgumentTypeCoercion */
            foreach (self::NAMESPACE_IMPORTS as $namespace => $alias) {
                if (\is_string($namespace)) {
                    $assembly = $assembly->withClassUsage($namespace, $alias);
                } else {
                    $assembly = $assembly->withClassUsage($alias);
                }
            }

            $result = $assembly->generate();

            $output->writeln(' <info>OK</info>');

            file_put_contents(self::GRAMMAR_OUTPUT, $result);

            $output->writeln(vsprintf(' - Saving <comment>%s</comment>', [
                realpath(self::GRAMMAR_OUTPUT) ?: self::GRAMMAR_OUTPUT,
            ]));
        } catch (\Throwable $e) {
            $output->writeln(' <error>FAIL</error>');

            throw $e;
        }

        return self::SUCCESS;
    }
}
