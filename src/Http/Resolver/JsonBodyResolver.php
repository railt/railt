<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Http\Request;
use Railt\Http\RequestInterface;
use Railt\Json\Json;

/**
 * Class JsonBodyResolver
 */
class JsonBodyResolver extends Resolver
{
    /**
     * @var int
     */
    private const JSON_DECODING_OPTIONS = \JSON_OBJECT_AS_ARRAY | \JSON_THROW_ON_ERROR;

    /**
     * @param ServerRequestInterface $request
     * @return RequestInterface|null
     * @throws \RuntimeException
     */
    public function resolve(ServerRequestInterface $request): ?RequestInterface
    {
        if (! $this->isJsonRequest($request)) {
            return null;
        }

        $body = $this->readJsonBody($request);

        if (! \is_array($body)) {
            return null;
        }

        if ($this->match($body)) {
            return new Request($this->query($body), $this->variables($body));
        }

        return null;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function isJsonRequest(ServerRequestInterface $request): bool
    {
        $haystack = (string)$request->getHeaderLine('Content-Type');

        foreach (['/json', '+json'] as $needle) {
            if (\stripos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @return array|mixed|object
     * @throws \RuntimeException
     */
    private function readJsonBody(ServerRequestInterface $request)
    {
        try {
            return Json::decode($request->getBody()->getContents(), self::JSON_DECODING_OPTIONS);
        } catch (\JsonException $e) {
            return [];
        }
    }
}
