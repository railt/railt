<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use GraphQL\Type\Definition\Directive;
use Railt\TypeSystem\DirectiveDefinition;

/**
 * @template-extends Builder<DirectiveDefinition, Directive>
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
