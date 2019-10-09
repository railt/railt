<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Pipeline\RequestHandlerInterface;

/**
 * Interface ConnectionInterface
 */
interface ConnectionInterface extends RequestHandlerInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return void
     */
    public function close(): void;

    /**
     * @return bool
     */
    public function isClosed(): bool;

    /**
     * @param \Closure $handler
     * @return void
     */
    public function onClose(\Closure $handler): void;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface;

    /**
     * @param RequestInterface $request
     * @param \Closure $notifier
     * @return \Generator|ResponseInterface[]
     */
    public function listen(RequestInterface $request, \Closure $notifier): \Generator;
}
