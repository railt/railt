<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Location;

use Railt\Dumper\Facade;

/**
 * Trait MutableLocationsProviderTrait
 *
 * @mixin MutableLocationsProviderInterface
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

        foreach ($locations as $name => $value) {
            $this->withLocation($name, $value);
        }

        return $this;
    }

    /**
     * @param int|LocationInterface $lineOrLocation
     * @param int $column
     * @return MutableLocationsProviderInterface
     */
    public function withLocation($lineOrLocation, $column = 1): MutableLocationsProviderInterface
    {
        $this->locations[] = $this->resolveLocation($lineOrLocation, $column);

        return $this;
    }

    /**
     * @param int|LocationInterface $lineOrLocation
     * @param int $column
     * @return LocationInterface
     */
    private function resolveLocation($lineOrLocation, $column): LocationInterface
    {
        switch (true) {
            case $lineOrLocation instanceof LocationInterface:
                return $lineOrLocation;

            case $column instanceof LocationInterface:
                return $column;

            case \is_int($lineOrLocation) && \is_int($column):
                return new Location($lineOrLocation, $column);
        }

        $error = 'First argument should be a location line or location instance, but %s given';
        $error = \sprintf($error, Facade::dump($lineOrLocation));

        throw new \InvalidArgumentException($error);
    }
}
