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
 * Class Stdout
 */
class Stdout implements LoggerInterface
{
    /**
     * @var float
     */
    private $lastCheckpoint;

    /**
     * Stdout constructor.
     */
    public function __construct()
    {
        $this->checkpoint();
    }

    /**
     * @return string
     */
    private function checkpoint(): string
    {
        $current = (float)microtime(true);
        $delta   = $this->lastCheckpoint !== null
            ? $current - $this->lastCheckpoint
            : 0;

        $this->lastCheckpoint = $current;

        return number_format($delta, 5);
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
        $this->log(Level::EMERGENCY, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
        $this->log(Level::ALERT, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        $this->log(Level::CRITICAL, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        $this->log(Level::ERROR, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        $this->log(Level::WARNING, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        $this->log(Level::NOTICE, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        $this->log(Level::INFO, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        $this->log(Level::DEBUG, $message, $context);
    }

    /**
     * @param int|string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $level   = is_int($level) ? Level::toString($level) : (string)$level;
        $message = sprintf('[%s %s] %s', (string)$level, $this->checkpoint(), $message);

        file_put_contents('php://stdout', $message . PHP_EOL .
            (count($context) ? print_r($context, true) : '')
        );
    }
}
