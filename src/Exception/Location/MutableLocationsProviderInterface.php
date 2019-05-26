<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exception\Location;

/**
 * Interface MutableLocationsProviderInterface
 */
interface MutableLocationsProviderInterface extends LocationsProviderInterface
{
    /**
     * @param LocationInterface ...$locations
     * @return MutableLocationsProviderInterface|$this
     */
    public function withLocation(LocationInterface ...$locations): self;

    /**
     * @param array $locations
     * @return MutableLocationsProviderInterface|$this
     */
    public function setLocations(array $locations): self;
}
