<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Provider;

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
     * @return string|null
     */
    public function getContentType(): ?string;

    /**
     * @return string
     */
    public function getBody(): string;
}
