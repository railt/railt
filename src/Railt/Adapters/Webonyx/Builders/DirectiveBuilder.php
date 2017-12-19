<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\DirectiveLocation;
use Railt\Reflection\Contracts\Definitions\Directive\Location;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;

/**
 * @property-read DirectiveDefinition $reflection
 */
class DirectiveBuilder extends TypeBuilder
{
    private const LOCATION_MAPPINGS = [
        Location::TARGET_QUERY               => DirectiveLocation::QUERY,
        Location::TARGET_MUTATION            => DirectiveLocation::MUTATION,
        Location::TARGET_SUBSCRIPTION        => DirectiveLocation::SUBSCRIPTION,
        Location::TARGET_FIELD               => DirectiveLocation::FIELD,
        Location::TARGET_FRAGMENT_DEFINITION => DirectiveLocation::FRAGMENT_DEFINITION,
        Location::TARGET_FRAGMENT_SPREAD     => DirectiveLocation::FRAGMENT_SPREAD,
        Location::TARGET_INLINE_FRAGMENT     => DirectiveLocation::INLINE_FRAGMENT,
    ];

    /**
     * @return Directive
     */
    public function build(): Directive
    {
        return new Directive([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'locations'   => $this->getLocations(),
            'args'        => []
        ]);
    }

    /**
     * TODO Arguments resolve
     *
     * @return array
     */
    private function getArguments(): array
    {
        return [];
    }

    /**
     * @return array
     */
    private function getLocations(): array
    {
        $result = [];

        foreach ($this->reflection->getLocations() as $location) {
            if (\array_key_exists($location, self::LOCATION_MAPPINGS)) {
                $result[] = self::LOCATION_MAPPINGS[$location];
            }
        }

        return $result;
    }
}
