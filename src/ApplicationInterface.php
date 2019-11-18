<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation;

use Phplrt\Contracts\Source\ReadableInterface;
use Railt\Container\ContainerInterface;
use Railt\Foundation\Console\ConsoleExecutableInterface;
use Railt\Foundation\Extension\ExtendableInterface;
use Railt\Foundation\Http\ConnectionInterface;

/**
 * Interface ApplicationInterface
 */
interface ApplicationInterface extends
    ConsoleExecutableInterface,
    ExtendableInterface,
    ContainerInterface
{
    /**
     * @return void
     */
    public function boot(): void;

    /**
     * @param string|resource|ReadableInterface $schema
     * @return ConnectionInterface
     */
    public function connect($schema): ConnectionInterface;

    /**
     * @return string
     */
    public function getVersion(): string;
}
