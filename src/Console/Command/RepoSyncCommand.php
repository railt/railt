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
        foreach ($this->getFiles() as $name => $file) {
            $output->writeln('Sync <info>' . $name . '</info>');

            foreach ($this->getDirectories() as $directory) {
                $output->write(' - <comment>' . \basename($directory) . '</comment>');

                $result = $this->render(File::fromPathname($file), $directory);

                if (isset(self::DIR_OUTPUT[$name])) {
                    $directory .= self::DIR_OUTPUT[$name];
                }

                if (! @\mkdir($directory, 0777, true) && ! \is_dir($directory)) {
                    throw new \LogicException($directory . ' directory is not writable');
                }

                \file_put_contents($directory . '/' . $name, $result);

                $output->writeln(' [<info>OK</info>]');
            }
        }
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
     * @return array|string[]
     */
    private function getDirectories(): array
    {
        $directories = \glob(self::DIR_PACKAGES . '/*');

        return \array_map(fn (string $dir): string => \realpath($dir), $directories);
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
