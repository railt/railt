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
use Railt\Extension\ExtensionInterface;

/**
 * Interface ApplicationInterface
 */
interface ApplicationInterface extends ContainerInterface
{
    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @param string|ExtensionInterface $extension
     * @return void
     */
    public function extend(string $extension): void;

    /**
     * @return int
     */
    public function cli(): int;
}
