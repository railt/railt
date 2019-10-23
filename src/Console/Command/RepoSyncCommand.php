<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console\Command;

use Phplrt\Source\File;
use Phplrt\Contracts\Source\ReadableInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal For development only
 */
class RepoSyncCommand extends Command
{
    /**
     * @var string
     */
    private const DIR_PACKAGES = __DIR__ . '/../../../packages';

    /**
     * @var string
     */
    private const DIR_STUBS = __DIR__ . '/SyncCommand';

    /**
     * @var string[]
     */
    private const DIR_OUTPUT = [
        'CONTRIBUTING.md'     => '/.github',
        'static-analysis.yml' => '/.github/workflows',
        'unit.yml'            => '/.github/workflows',
        'feature.yml'         => '/.github/workflows',
    ];

    /**
     * @var string[]
     */
    private const IGNORED = [
        'Contracts',
    ];

    /**
     * @var string
     */
    private const MESSAGE_IGNORE = ' <error> SKIP </error>';

    /**
     * @var string
     */
    private const MESSAGE_OK = ' <info> OK </info>';

    /**
     * @var string
     */
    private const MESSAGE_PACKAGE = 'Package <<info>%s</info>>';

    /**
     * @var string
     */
    private const MESSAGE_FILE = '  | File <comment>%s</comment>';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'repo:sync';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return '[DEVELOPMENT] The command to sync all files from main repository to subpackages';
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getDirectories() as $directory) {
            $output->writeln(\sprintf(self::MESSAGE_PACKAGE, \basename($directory)));

            foreach ($this->getFiles() as $name => $file) {
                $outDirectory = $directory;

                $output->write(\vsprintf(self::MESSAGE_FILE, [
                    './packages/' . \basename($directory) . '/' . $name,
                ]));

                if (\in_array(\basename($outDirectory), self::IGNORED, true)) {
                    $output->writeln(self::MESSAGE_IGNORE);

                    continue;
                }

                $result = $this->render(File::fromPathname($file), $outDirectory);

                if (isset(self::DIR_OUTPUT[$name])) {
                    $outDirectory .= self::DIR_OUTPUT[$name];
                }

                if (! @\mkdir($outDirectory, 0777, true) && ! \is_dir($outDirectory)) {
                    throw new \LogicException($outDirectory . ' directory is not writable');
                }

                \file_put_contents($outDirectory . '/' . $name, $result);

                $output->writeln(self::MESSAGE_OK);
            }
        }
    }

    /**
     * @return array|string[]
     */
    private function getDirectories(): array
    {
        $directories = \glob(self::DIR_PACKAGES . '/*');

        return \array_map(fn (string $dir): string => \realpath($dir), $directories);
    }

    /**
     * @return array|string[]
     */
    private function getFiles(): array
    {
        $mapper = static fn (string $value): array => [\basename($value, '.stub'), $value];

        return \array_column(\array_map($mapper, \glob(self::DIR_STUBS . '/*.stub')), 1, 0);
    }

    /**
     * @param ReadableInterface $file
     * @param string $dirname
     * @return string
     */
    private function render(ReadableInterface $file, string $dirname): string
    {
        return \str_replace([
            '${package}',
            '${name}',
        ], [
            \strtolower(\basename($dirname)),
            \basename($dirname),
        ], $file->getContents());
    }
}
