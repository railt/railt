<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Psr\Log\LoggerInterface;

/**
 * Trait Loggable
 */
trait Loggable
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param string $message
     * @param array ...$args
     */
    protected function log(string $message, ...$args): void
    {
        if ($this->logger !== null) {
            $this->logger->debug(\vsprintf($message, $args));
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
