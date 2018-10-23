<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PsrHttpProvider
 */
class PsrHttpProvider extends Provider
{
    /**
     * Real header name which provides by client instead of
     * server super-global variable CONTENT_TYPE key.
     *
     * @var string
     */
    private const REAL_CONTENT_TYPE_HEADER_NAME = 'Content-Type';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var array|string[]
     */
    private $bodies;

    /**
     * PsrHttpProvider constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    protected function isJson(): bool
    {
        foreach ($this->getContentTypes() as $type) {
            if (\is_string($type) && $this->matchJson($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return iterable|string[]
     */
    private function getContentTypes(): iterable
    {
        $headerNames = [static::CONTENT_TYPE_KEY, self::REAL_CONTENT_TYPE_HEADER_NAME];

        foreach ($headerNames as $name) {
            $value = $this->request->getHeader($name);

            if (\is_iterable($value)) {
                yield from $value;
            } elseif (\is_string($value)) {
                yield $value;
            }
        }
    }

    /**
     * @return array
     */
    protected function getJson(): array
    {
        try {
            $body   = $this->request->getBody();
            $bodyId = \spl_object_hash($body);

            if (! isset($this->bodies[$bodyId])) {
                $this->bodies[$bodyId] = $body->getContents();
            }

            return $this->parseJson($this->bodies[$bodyId]);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * @return iterable
     */
    protected function getRequestArguments(): iterable
    {
        return $this->request instanceof ServerRequestInterface ? \array_merge($this->request->getQueryParams(),
            (array)$this->request->getParsedBody()) : [];
    }
}
