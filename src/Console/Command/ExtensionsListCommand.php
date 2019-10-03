<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console\Command;

use Railt\Extension\ExtensionInterface;
use Railt\Extension\RepositoryInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExtensionsListCommand
 */
class ExtensionsListCommand extends Command
{
    /**
     * @var RepositoryInterface|ExtensionInterface[]
     */
    private RepositoryInterface $extensions;

    /**
     * ExtensionsListCommand constructor.
     *
     * @param RepositoryInterface $extensions
     */
    public function __construct(RepositoryInterface $extensions)
    {
        $this->extensions = $extensions;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'extensions:list';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Lists all loaded extensions';
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);

        $table->setHeaders(['Name', 'Description', 'Version', 'Status']);

        foreach ($this->extensions as $extension) {
            $table->addRow([
                $extension->getName(),
                $extension->getDescription(),
                $extension->getVersion(),
                $extension->getStatus()
            ]);
        }

        $table->render();
    }
}
