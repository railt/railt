<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Location;

use Ramsey\Collection\CollectionInterface;

/**
 * Interface LocationsProviderInterface
 */
interface LocationsProviderInterface
{
    /**
     * @return CollectionInterface|LocationInterface[]
     */
    public function getLocations(): CollectionInterface;

    /**
     * @param int|LocationInterface $lineOrLocation
     * @param int $column
     * @return LocationsProviderInterface|$this
     */
    public function withLocation($lineOrLocation, int $column = 1): self;

    /**
     * @param iterable|int[]|LocationInterface[] $locations
     * @return LocationsProviderInterface|$this
     */
    public function withLocations(iterable $locations = []): self;

    /**
     * @return bool
     */
    public function hasLocations(): bool;
}
