<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Error;

use Railt\Dumper\Facade;
use Railt\Contracts\Http\Error\SourceLocationInterface;
use Railt\Contracts\Http\Error\SourceLocationsProviderInterface;

/**
 * Trait LocationsTrait
 *
 * @mixin SourceLocationsProviderInterface
 */
trait LocationsTrait
{
    /**
     * @var array|SourceLocationInterface[]
     */
    protected array $locations = [];

    /**
     * {@inheritDoc}
     */
    public function hasLocations(): bool
    {
        return $this->locations !== [];
    }

    /**
     * {@inheritDoc}
     */
    public function getLocations(): iterable
    {
        if ($this->locations === []) {
            return [new SourceLocation(1, 1)];
        }

        return $this->locations;
    }

    /**
     * @param iterable|SourceLocationInterface[]|int[] $locations
     * @return void
     */
    protected function setLocations(iterable $locations = []): void
    {
        foreach ($locations as $lineOrLocation => $column) {
            switch (true) {
                case \is_int($lineOrLocation):
                    $this->locations[] = new SourceLocation($lineOrLocation, $column);
                    break;

                case $lineOrLocation instanceof SourceLocationInterface:
                    $this->locations[] = $lineOrLocation;
                    break;

                default:
                    $error = 'Argument should be an integer type or instance of %s, but %s given';
                    $error = \sprintf($error, SourceLocationInterface::class, Facade::dump($lineOrLocation));

                    throw new \InvalidArgumentException($error);
            }
        }
    }
}
