<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime;

use Psr\Log\LoggerInterface;

/**
 * Trait Loggable
 * @package Serafim\Railgun\Runtime
 */
trait Loggable
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param null|LoggerInterface $logger
     * @return $this
     */
    public function withLogger(?LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param string $message
     */
    public function debug(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->debug($message);
        }
    }

    /**
     * @param string $message
     */
    public function notice(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->notice($message);
        }
    }

    /**
     * @param string $message
     */
    public function error(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->error($message);
        }
    }
}
