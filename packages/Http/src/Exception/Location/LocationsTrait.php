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
use Ramsey\Collection\CollectionInterface;

/**
 * Trait LocationsTrait
 *
 * @mixin LocationsProviderInterface
 */
trait LocationsTrait
{
    /**
     * @var CollectionInterface|LocationInterface[]
     */
    protected CollectionInterface $locations;

    /**
     * {@inheritDoc}
     */
    public function withLocation($lineOrLocation, int $column = 1): LocationsProviderInterface
    {
        @\trigger_error(
            'Locations mutation changes the self object, because ' .
            'cloning is not allowed for exceptions'
        );

        switch (true) {
            case \is_int($lineOrLocation):
                $this->locations->add(new Location($lineOrLocation, $column));
                break;

            case $lineOrLocation instanceof LocationInterface:
                $this->locations->add($lineOrLocation);
                break;

            default:
                $error = 'First argument should be an integer type or instance of %s, but %s given';
                $error = \sprintf($error, LocationInterface::class, Facade::dump($lineOrLocation));

                throw new \InvalidArgumentException($error);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasLocations(): bool
    {
        return ! $this->locations->isEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function getLocations(): CollectionInterface
    {
        return $this->locations;
    }

    /**
     * @param iterable|CollectionInterface $locations
     * @return void
     */
    protected function setLocations(iterable $locations = []): void
    {
        if ($locations instanceof CollectionInterface) {
            $locations = $locations->toArray();
        }

        $this->locations = new LocationsCollection($locations);
    }
}
