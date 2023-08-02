<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\Directive;
use Railt\Executor\Webonyx\Builder\Builder;
use Railt\TypeSystem\Definition\DirectiveDefinition;

/**
 * @template-extends Builder<DirectiveDefinition, Directive>
 *
 * @psalm-suppress RedundantConditionGivenDocblockType
 */
final class DirectiveBuilder extends Builder
{
    public function build(object $input): Directive
    {
        assert($input instanceof DirectiveDefinition, self::typeError(
            DirectiveDefinition::class,
            $input,
        ));

        return new Directive([
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'locations' => $this->buildLocations($input),
        ]);
    }

    private function buildLocations(DirectiveDefinition $directive): array
    {
        $result = [];

        foreach ($directive->getLocations() as $location) {
            $result[] = $location->getName();
        }

        return $result;
    }
}
