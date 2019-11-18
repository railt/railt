<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation\Console\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal For development only
 */
class RepoMergeCommand extends Command
{
    /**
     * @var string
     */
    private const PATH_MONOREPO = __DIR__ . '/../../../vendor/symplify/monorepo-builder/bin/monorepo-builder';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'repo:merge';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return '[DEVELOPMENT] The command to merge all the dependencies (packages) in the main composer.json file';
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new Process(['php', \realpath(self::PATH_MONOREPO), 'merge']))
            ->run(fn (string $_, string $buffer) => $output->write($buffer));

        (new Process(['composer', 'update', '--no-scripts']))
            ->run(fn (string $_, string $buffer) => $output->write($buffer));
    }
}
