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
 * Trait LocationsProviderTrait
 */
trait LocationsProviderTrait
{
    /**
     * @var array|LocationInterface[]
     */
    protected array $locations = [];

    /**
     * @return array|LocationInterface[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * @return bool
     */
    public function hasLocations(): bool
    {
        return \count($this->locations) > 0;
    }
}
