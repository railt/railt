<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Adapters;

use Serafim\Railgun\Contracts\TypesRegistryInterface;

/**
 * Interface EndpointDriverInterface
 * @package Serafim\Railgun\Contracts\Adapters
 */
interface EndpointDriverInterface extends EndpointInterface
{
    /**
     * EndpointDriverInterface constructor.
     * @param string $name
     * @param TypesRegistryInterface $registry
     */
    public function __construct(string $name, TypesRegistryInterface $registry);

    /**
     * @return bool
     */
    public static function isSupportedBy(): bool;
}
