<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Support;

/**
 * Trait JsonContentTypeHelper
 */
trait JsonContentTypeHelper
{
    /**
     * @param string $contentType
     * @return bool
     */
    protected function isJson(string $contentType): bool
    {
        return $this->contains($contentType, '/json', '+json');
    }

    /**
     * @param string $haystack
     * @param string[] ...$needles
     * @return bool
     */
    private function contains(string $haystack, string ...$needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && \mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
