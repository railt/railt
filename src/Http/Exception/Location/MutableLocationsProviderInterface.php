<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Location;

/**
 * Interface MutableLocationsProviderInterface
 */
interface MutableLocationsProviderInterface extends LocationsProviderInterface
{
    /**
     * @param int|LocationInterface $lineOrLocation
     * @param int $column
     * @return MutableLocationsProviderInterface
     */
    public function withLocation($lineOrLocation, $column = 1): self;

    /**
     * @param array|int[]|LocationInterface[] $locations
     * @return MutableLocationsProviderInterface|$this
     */
    public function setLocations(array $locations): self;
}
