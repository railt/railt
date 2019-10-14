<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory\Provider;

/**
 * Class DataProvider
 */
final class DataProvider implements ProviderInterface
{
    /**
     * @var array
     */
    private array $get;

    /**
     * @var array
     */
    private array $post;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var \Closure
     */
    private \Closure $read;

    /**
     * DataProvider constructor.
     *
     * @param array $query
     * @param array $post
     * @param array $headers
     * @param \Closure $read
     */
    public function __construct(array $query, array $post, array $headers, \Closure $read)
    {
        $this->get = $query;
        $this->post = $post;
        $this->headers = $headers;
        $this->read = $read;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryArguments(): array
    {
        return $this->get;
    }

    /**
     * {@inheritDoc}
     */
    public function getPostArguments(): array
    {
        return $this->post;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader(string $name): array
    {
        return (array)($this->headers[$name] ?? []);
    }

    /**
     * {@inheritDoc}
     */
    public function getBody(): string
    {
        return (string)($this->read)();
    }
}
