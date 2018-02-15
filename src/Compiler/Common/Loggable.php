<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Common;

use Psr\Log\LoggerInterface;

/**
 * Trait Loggable
 */
trait Loggable
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface|null $logger
     */
    protected function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;

        if ($logger && ! \property_exists($logger, 'depth')) {
            $logger->depth = 0;
        }
    }

    /**
     * @param string $message
     * @param array ...$params
     * @return bool
     */
    protected function writeInline(string $message, ...$params): bool
    {
        if ($this->logger !== null) {
            $inline = function ($param): string {
                $param = \preg_replace('/\h{2,}/', '\s', (string)$param);

                return \str_replace(["\n", "\r", "\t", "\0"], ['\n', '\r', '\t', '\0'], (string)$param);
            };

            $this->write($inline($message), ...\array_map($inline, $params));
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function loggerDepthIn(): bool
    {
        if ($this->logger !== null) {
            $this->logger->depth++;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function loggerDepthOut(): bool
    {
        if ($this->logger !== null) {
            $this->logger->depth--;
        }

        return true;
    }

    /**
     * @param string $message
     * @param array|string[]|int[]|float[] ...$params
     * @return bool
     */
    protected function write(string $message, ...$params): bool
    {
        if ($this->logger !== null) {
            $this->logger->debug(
                \str_repeat('    ', $this->logger->depth) .
                \vsprintf($message, $params)
            );
        }

        return true;
    }
}
