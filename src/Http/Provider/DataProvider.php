<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

/**
 * Class DataProvider
 */
class DataProvider implements ProviderInterface
{
    /**
     * @var array
     */
    private $query;

    /**
     * @var array
     */
    private $post;

    /**
     * @var string|null
     */
    private $contentType;

    /**
     * @var string
     */
    private $body = '';

    /**
     * DataProvider constructor.
     * @param array $query
     * @param array $post
     */
    public function __construct(array $query = [], array $post = [])
    {
        $this->query = $query;
        $this->post = $post;
    }

    /**
     * @param array $query
     * @param array $post
     * @return DataProvider
     */
    public static function new(array $query = [], array $post = []): self
    {
        return new static($query, $post);
    }

    /**
     * @return array
     */
    public function getQueryArguments(): array
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getPostArguments(): array
    {
        return $this->post;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return (string)$this->contentType;
    }

    /**
     * @param null|string $contentType
     * @return DataProvider|$this
     */
    public function withContentType(?string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return DataProvider|$this
     */
    public function withBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}
