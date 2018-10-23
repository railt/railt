<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends MessageInterface, \JsonSerializable
{
    /**
     * @var string Positive status code
     */
    public const STATUS_CODE_SUCCESS = 200;

    /**
     * @var string Negative status code
     */
    public const STATUS_CODE_ERROR = 500;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @param MessageInterface $message
     * @return ResponseInterface
     */
    public function addMessage(MessageInterface $message): self;

    /**
     * @return string
     */
    public function render(): string;

    /**
     * @return void
     */
    public function send(): void;

    /**
     * @return bool
     */
    public function isBatched(): bool;

    /**
     * @return iterable|MessageInterface[]
     */
    public function getMessages(): iterable;
}
