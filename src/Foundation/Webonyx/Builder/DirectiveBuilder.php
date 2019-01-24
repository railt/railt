<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\FieldArgument;
use Railt\Foundation\Webonyx\Builder\Common\TypeResolverTrait;
use Railt\SDL\Contracts\Definitions\Directive\Location;
use Railt\SDL\Contracts\Definitions\DirectiveDefinition;

/**
 * Class DirectiveBuilder
 *
 * @property DirectiveDefinition $reflection
 */
class DirectiveBuilder extends Builder
{
    use TypeResolverTrait;

    /**
     * @var string[]
     */
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
        return new Directive(\array_filter([
            'name'        => $this->reflection->getName(),
            'description' => $this->reflection->getDescription(),
            'locations'   => \iterator_to_array($this->getPublicLocations()),
            'args'        => $this->getFieldArguments()
        ]));
    }

    /**
     * @return \Generator|string[]
     */
    private function getPublicLocations(): \Generator
    {
        foreach ($this->reflection->getLocations() as $location) {
            if (isset(self::LOCATION_MAPPINGS[$location])) {
                yield self::LOCATION_MAPPINGS[$location];
            }
        }
    }

    /**
     * @return \Closure
     */
    private function getFieldArguments(): \Closure
    {
        return function (): array {
            $result = [];

            foreach ($this->reflection->getArguments() as $argument) {
                $field = new FieldArgument([
                    'name'         => $argument->getName(),
                    'type'         => $this->buildTypeHint($argument),
                    'description'  => $argument->getDescription()
                ]);

                if ($argument->hasDefaultValue()) {
                    $field['defaultValue'] = $argument->getDefaultValue();
                }

                $result[] = $field;
            }

            return $result;
        };
    }
}
