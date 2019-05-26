<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exception;

use Phplrt\Position\PositionInterface;
use Railt\Exception\Location\LocationsProviderInterface;
use Railt\Exception\Path\PathProviderInterface;
use Railt\HttpExtension\ExtensionProviderInterface;

/**
 * Interface GraphQLExceptionInterface
 */
interface GraphQLExceptionInterface extends
    \Throwable,
    \JsonSerializable,
    PositionInterface,
    PathProviderInterface,
    LocationsProviderInterface,
    ExtensionProviderInterface
{
    /**
     * @return bool
     */
    public function isPublic(): bool;
}
