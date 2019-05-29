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
 * Trait MutableLocationsProviderTrait
 */
trait MutableLocationsProviderTrait
{
    use LocationsProviderTrait;

    /**
     * @param array $locations
     * @return MutableLocationsProviderInterface|$this
     */
    public function setLocations(array $locations): MutableLocationsProviderInterface
    {
        $this->locations = [];

        return $this->withLocation(...$locations);
    }

    /**
     * @param LocationInterface ...$locations
     * @return MutableLocationsProviderInterface|$this
     */
    public function withLocation(LocationInterface ...$locations): MutableLocationsProviderInterface
    {
        $this->locations = \array_merge($this->locations, $locations);

        return $this;
    }
}
