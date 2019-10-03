<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Http\Exception\Location\LocationsProviderInterface;
use Railt\Http\Exception\Path\PathProviderInterface;

/**
 * Interface GraphQLExceptionInterface
 */
interface GraphQLExceptionInterface extends
    \Throwable,
    \JsonSerializable,
    PathProviderInterface,
    LocationsProviderInterface
{
    /**
     * @return bool
     */
    public function isPublic(): bool;
}
