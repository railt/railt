<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Definitions;

use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\Directive\Location;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\SDL\Exceptions\TypeConflictException;

/**
 * Class DirectiveDefinitionValidator
 */
class DirectiveDefinitionValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof DirectiveDefinition;
    }

    /**
     * @param Definition|DirectiveDefinition $definition
     * @return void
     */
    public function validate(Definition $definition): void
    {
        $this->getCallStack()->push($definition);

        $locations = $definition->getLocations();

        foreach ($locations as $location) {
            $isValidLocation = $this->isSDLLocation($location) || $this->isQueryLocation($location);

            if (! $isValidLocation) {
                $error = \vsprintf('Trying to define directive %s, but %s location is invalid', [
                    $definition,
                    $location,
                ]);
                throw new TypeConflictException($error, $this->getCallStack());
            }
        }

        $this->getCallStack()->pop();
    }

    /**
     * @param string $location
     * @return bool
     */
    private function isSDLLocation(string $location): bool
    {
        return \in_array($location, Location::TARGET_GRAPHQL_SDL, true);
    }

    /**
     * @param string $location
     * @return bool
     */
    private function isQueryLocation(string $location): bool
    {
        return \in_array($location, Location::TARGET_GRAPHQL_QUERY, true);
    }
}
