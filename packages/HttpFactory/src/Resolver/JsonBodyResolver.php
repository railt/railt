<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\HttpFactory\Resolver;

use Railt\Contracts\HttpFactory\Provider\ProviderInterface;

/**
 * Class JsonBodyReader
 */
class JsonBodyResolver extends Resolver
{
    /**
     * @param ProviderInterface $provider
     * @return bool
     */
    protected function match(ProviderInterface $provider): bool
    {
        return $this->isProvidesJsonContent($provider->getHeader('content-type'));
    }

    /**
     * @param array $contentTypes
     * @return bool
     */
    private function isProvidesJsonContent(array $contentTypes): bool
    {
        foreach ($contentTypes as $contentType) {
            if ($this->contains($contentType, ['/json', '+json'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $haystack
     * @param array|string[] $needles
     * @return bool
     */
    private function contains(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && \strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ProviderInterface $provider
     * @return array
     */
    protected function read(ProviderInterface $provider): array
    {
        return $this->parseJson($provider->getBody());
    }

    /**
     * @param string $json
     * @return array
     */
    protected function parseJson(string $json): array
    {
        try {
            return \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return [];
        }
    }
}
