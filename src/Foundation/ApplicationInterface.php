<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Container\ContainerInterface;
use Railt\Foundation\Config\ConfigurationInterface;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Io\Readable;
use Railt\Debug\Debuggable;
use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Interface ApplicationInterface
 */
interface ApplicationInterface extends Debuggable
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @param ConfigurationInterface $config
     * @return ApplicationInterface|$this
     */
    public function configure(ConfigurationInterface $config): self;

    /**
     * @return ConsoleApplication
     */
    public function getConsoleApplication(): ConsoleApplication;

    /**
     * @param string|ExtensionInterface $extension
     * @return ApplicationInterface|$this
     */
    public function extend(string $extension): self;

    /**
     * @param Readable $schema
     * @return ConnectionInterface
     */
    public function connect(Readable $schema): ConnectionInterface;
}
