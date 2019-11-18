<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\HttpFactory\Provider;

/**
 * Interface ProviderInterface
 */
interface ProviderInterface
{
    /**
     * @return array
     */
    public function getQueryArguments(): array;

    /**
     * @return array
     */
    public function getPostArguments(): array;

    /**
     * @param string $name
     * @return array
     */
    public function getHeader(string $name): array;

    /**
     * @return string
     */
    public function getBody(): string;
}
