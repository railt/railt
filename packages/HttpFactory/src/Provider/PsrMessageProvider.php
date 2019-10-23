<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory\Provider;

use Psr\Http\Message\MessageInterface;
use Railt\Contracts\HttpFactory\Provider\ProviderInterface;

/**
 * Class PsrMessageProvider
 */
class PsrMessageProvider implements ProviderInterface
{
    /**
     * @var MessageInterface
     */
    protected MessageInterface $message;

    /**
     * PsrMessageProvider constructor.
     *
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryArguments(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getPostArguments(): array
    {
        return [];
    }

    /**
     * @param string $name
     * @return array
     */
    public function getHeader(string $name): array
    {
        return (array)$this->message->getHeader($name);
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        $body = $this->message->getBody();

        try {
            return $body->getContents();
        } catch (\RuntimeException $e) {
            return '';
        }
    }
}
