<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;

/**
 * @mixin LoggerAwareInterface
 */
trait ParserLoggerTrait
{
    use LoggerAwareTrait;

    /**
     * @var int
     */
    protected int $depth = 0;

    /**
     * @var int
     */
    protected int $lastDepth = 0;

    /**
     * @var int
     */
    protected int $assertions = 0;

    /**
     * @param ReadableInterface $source
     * @return void
     */
    protected function prepare(ReadableInterface $source): void
    {
        $this->assertions = 0;

        $this->logIfPossible(static function (LoggerInterface $logger) use ($source) {
            $suffix = $source instanceof FileInterface
                ? $source->getPathname()
                : ' of source';

            $logger->info('Start parsing ' . $suffix, ['source' => $source]);
        });
    }

    /**
     * @param TokenInterface $token
     * @param string|int $state
     * @return void
     */
    protected function depthIn($state, TokenInterface $token): void
    {
        $this->logIfPossible(function (LoggerInterface $logger) use ($state, $token): void {
            $prefix = $this->lastDepth === $this->depth ? ' ╰ ' : '   ';

            $logger->debug($this->logFormat($prefix . $state . ' ' . $this->dump($token->getValue())));
        });

        $this->depth++;
        $this->lastDepth = $this->depth;
    }

    /**
     * @param \Closure $fn
     * @return void
     */
    protected function logIfPossible(\Closure $fn): void
    {
        if ($this->logger) {
            $fn($this->logger);
        }
    }

    /**
     * @param string $message
     * @return string
     */
    private function logFormat(string $message): string
    {
        $prefix = \sprintf('%4s. | ', $this->assertions++);

        return $prefix . \str_repeat('  ', $this->depth) . $message;
    }

    /**
     * @param string|int $state
     * @param object|mixed $result
     * @return void
     */
    protected function depthOut($state, $result): void
    {
        $this->depth--;

        $this->logIfPossible(function (LoggerInterface $logger) use ($state, $result): void {
            if ($result !== null) {
                $logger->debug($this->logFormat(' ◀ ' . $state . ' ' . $this->dump($result)));

                return;
            }

            $logger->debug($this->logFormat(' x ' . $state));
        });
    }

    /**
     * @param mixed $result
     * @return string
     */
    private function dump($result): string
    {
        if (\class_exists(\Railt\Dumper\Facade::class)) {
            return \Railt\Dumper\Facade::dump($result);
        }

        \ob_start();
        \var_dump($result);

        return \ob_get_clean();
    }
}
