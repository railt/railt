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
use GraphQL\Type\Definition\FieldArgument;
use Railt\SDL\Contracts\Definitions\Directive\Location;
use Railt\SDL\Contracts\Definitions\DirectiveDefinition;

/**
 * @property DirectiveDefinition $reflection
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
     * @throws \Exception
     */
    public function build(): Directive
    {
        return new Directive([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'locations'   => $this->getLocations(),
            'args'        => $this->getArguments(),
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getArguments(): array
    {
        $arguments = ArgumentBuilder::buildArguments($this->reflection, $this->getRegistry(), $this->events);

        return \array_map(function ($argument): FieldArgument {
            return new FieldArgument($argument);
        }, $arguments);
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
