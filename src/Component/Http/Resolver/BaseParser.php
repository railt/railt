<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Resolver;

use Railt\Component\Http\Request;
use Railt\Component\Http\RequestInterface;
use Railt\Component\Json\Json;

/**
 * Class BaseParser
 */
abstract class BaseParser implements ResolverInterface
{
    /**
     * @param array $data
     * @return RequestInterface
     * @throws \LogicException
     */
    protected function fromArray(array $data): RequestInterface
    {
        $query = new Request($data[static::QUERY_ARGUMENT]);

        if (\is_array($data[static::VARIABLES_ARGUMENT] ?? null)) {
            $query->withVariables($data[static::VARIABLES_ARGUMENT]);
        }

        if (\is_string($data[static::VARIABLES_ARGUMENT] ?? null)) {
            $query->withVariables($this->parseJson((string)$data[static::VARIABLES_ARGUMENT]));
        }

        if (\is_string($data[static::OPERATION_ARGUMENT] ?? null)) {
            $query->withOperation((string)$data[static::OPERATION_ARGUMENT]);
        }

        return $query;
    }

    /**
     * @param string $contentType
     * @return bool
     */
    protected function matchJson(string $contentType): bool
    {
        return $this->contains($contentType, '/json', '+json');
    }

    /**
     * @param string $json
     * @param bool $throws
     * @return array
     * @throws \LogicException
     */
    protected function parseJson(string $json, bool $throws = false): array
    {
        try {
            return Json::decoder()
                ->withOptions(\JSON_OBJECT_AS_ARRAY)
                ->decode($json);
        } catch (\JsonException $e) {
            return [];
        }
    }

    /**
     * @param string $haystack
     * @param string ...$needles
     * @return bool
     */
    private function contains(string $haystack, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && \mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}
