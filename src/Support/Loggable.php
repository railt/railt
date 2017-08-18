<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Support;

use Psr\Log\LoggerInterface;

/**
 * Trait Loggable
 * @package Railgun\Support
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
    public function debugMessage(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->debug($message);
        }
    }

    /**
     * @param string $message
     */
    public function noticeMessage(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->notice($message);
        }
    }

    /**
     * @param string $message
     */
    public function errorMessage(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->error($message);
        }
    }
}
