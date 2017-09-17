<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support\Log;

use Psr\Log\LoggerInterface;

/**
 * Interface AllowsLoggerAddition
 */
interface AllowsLoggerAddition
{
    /**
     * @param null|LoggerInterface $logger
     * @return AllowsLoggerAddition|$this
     */
    public function withLogger(?LoggerInterface $logger);

    /**
     * @return null|LoggerInterface
     */
    public function getLogger(): ?LoggerInterface;
}
