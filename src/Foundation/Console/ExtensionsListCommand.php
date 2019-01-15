<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console;

use Railt\Foundation\Extension\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExtensionsListCommand
 */
class ExtensionsListCommand extends Command
{
    /**
     * @var Repository
     */
    private $extensions;

    /**
     * ExtensionsListCommand constructor.
     * @param Repository $extensions
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(Repository $extensions)
    {
        parent::__construct();

        $this->extensions = $extensions;
    }

    /**
     * @param InputInterface $in
     * @param OutputInterface $out
     * @throws \Throwable
     */
    public function execute(InputInterface $in, OutputInterface $out): void
    {
        $table = new Table($out);
        $table->setHeaders(['Name', 'Version', 'Status', 'Class', 'Description']);

        foreach ($this->extensions->all() as $extension) {
            $table->addRow([
                $extension->getName(),
                $extension->getVersion(),
                $extension->getStatus(),
                \get_class($extension),
                $extension->getDescription(),
            ]);
        }

        $table->render();
    }

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('extensions:list');
        $this->setDescription('Show list of registered extensions');
    }
}
