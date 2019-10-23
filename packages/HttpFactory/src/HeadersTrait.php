<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

/**
 * Trait HeadersTrait
 */
trait HeadersTrait
{
    /**
     * @return array
     */
    protected function getGlobalHeaders(): array
    {
        $result = $_SERVER ?? [];

        if (\function_exists('\\getallheaders')) {
            $result = \array_merge(\getallheaders(), $result);
        }

        if (\function_exists('\\apache_request_headers')) {
            $result = \array_merge(\apache_request_headers(), $result);
        }

        return $this->normalizeHeaders($result);
    }

    /**
     * @param array $headers
     * @return array
     */
    protected function normalizeHeaders(array $headers): array
    {
        $result = [];

        foreach ($headers as $key => $value) {
            if (! \is_string($key) || $value === '') {
                continue;
            }

            // Apache prefixes environment variables with REDIRECT_
            // if they are added by rewrite rules
            if (\strpos($key, 'REDIRECT_') === 0) {
                $key = \substr($key, 9);

                // We will not overwrite existing variables with the
                // prefixed versions, though
                if (\array_key_exists($key, $headers)) {
                    continue;
                }
            }

            if (\strpos($key, 'HTTP_') === 0) {
                $name = \str_replace('_', '-', \strtolower(\substr($key, 5)));
                $result[$name] = $value;

                continue;
            }

            if (\strpos($key, 'CONTENT_') === 0) {
                $name = \str_replace('_', '-', \strtolower($key));
                $result[$name] = $value;

                continue;
            }
        }

        return $result;
    }
}
