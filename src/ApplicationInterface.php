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
use Railt\Foundation\Extension\ExtendableInterface;
use Railt\Foundation\Console\ConsoleExecutableInterface;

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
     * @return string
     */
    public function getVersion(): string;
}
