<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Support;

use Serafim\Railgun\Contracts\ContainsNameInterface;

/**
 * Trait NameBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Support
 */
trait NameBuilder
{
    /**
     * @param ContainsNameInterface $name
     * @param null|string $default
     * @return array
     */
    private function makeName(ContainsNameInterface $name, ?string $default = null): array
    {
        return [
            'name'        => $default ?? $name->getName(),
            'description' => $name->getDescription(),
        ];
    }
}
