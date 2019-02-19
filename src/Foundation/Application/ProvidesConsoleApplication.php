<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Interface ProvidesConsoleApplication
 */
interface ProvidesConsoleApplication
{
    /**
     * @return ConsoleApplication
     */
    public function getConsoleApplication(): ConsoleApplication;
}
