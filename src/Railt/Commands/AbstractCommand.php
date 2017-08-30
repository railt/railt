<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package Railt\Commands
 */
abstract class AbstractCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'dummy:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $out;

    /**
     * @var array
     */
    private $shortcuts = [
        'h',
        'q',
        'V',
        'n',
        'v',
        'vv',
        'vvv',
    ];

    /**
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName($this->name);
        $this->setDescription($this->getDescription());

        foreach ($this->arguments as $argument => $default) {
            $this->addArgument($argument, null, '', $default);
        }

        foreach ($this->options as $option => $description) {
            $this->addOption($option, $this->shortcut($option), null, $description);
        }
    }

    /**
     * @param string $cmd
     * @return null|string
     */
    private function shortcut(string $cmd): ?string
    {
        $size = 1;

        do {
            $current = mb_substr($cmd, 0, $size);
            if (!in_array($current, $this->shortcuts, true)) {
                $this->shortcuts[] = $current;
                return $current;
            }
        } while ($size++ <= mb_strlen($cmd));

        return null;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        [$this->input, $this->out] = [$input, $output];

        $this->writeBootstrapMessage();

        return $this->handle();
    }

    /**
     * @return void
     */
    private function writeBootstrapMessage(): void
    {
        $message  = '  Booting "' . $this->name . '" command.';
        $splitter = str_repeat('-', strlen($message) + 2);

        $this->out->writeln($splitter);
        $this->out->writeln($message);
        $this->out->writeln($splitter);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function projectRoot(): string
    {
        $reflection = new \ReflectionClass(self::class);

        return dirname($reflection->getFileName(), 4);
    }

    /**
     * @return mixed
     */
    abstract public function handle();
}
