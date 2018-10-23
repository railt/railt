<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider\Support;

/**
 * Trait JsonRequestDetector
 */
trait JsonRequestDetector
{
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
            $data = @\json_decode($json, true, 64);

            // Since PHP >= 7.3 parsing json containing errors will throws
            // an exception. It is necessary to handle these cases.
        } catch (\Throwable $e) {
            if ($throws) {
                throw new \LogicException($e->getMessage(), $e->getCode(), $e);
            }

            $data = [];
        }

        // If PHP is lower or equal to version 7.2, then we must
        // handle the error in the old good way.
        if (\json_last_error() !== \JSON_ERROR_NONE) {
            if ($throws) {
                throw new \LogicException(\json_last_error_msg(), \json_last_error());
            }

            $data = [];
        }

        return $data;
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
