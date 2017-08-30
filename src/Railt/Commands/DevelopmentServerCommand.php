<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Commands;

/**
 * Class DevelopmentServerCommand
 * @package Railt\Commands
 */
class DevelopmentServerCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $name = 'devel:server';

    /**
     * @var string
     */
    protected $description = 'Bootstrap an example project';

    /**
     * @var array
     */
    protected $arguments = [
        'host' => '127.0.0.1',
        'port' => '8000'
    ];

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function handle(): void
    {
        [$host, $port] = [$this->input->getArgument('host'), $this->input->getArgument('port')];

        $this->out->writeln('Startup example at http://' . $host . ':' . $port);

        $command = 'cd "%s" && php -S ' . $host . ':' . $port;
        shell_exec(sprintf($command, $this->projectRoot() . '/resources/example'));
    }
}
