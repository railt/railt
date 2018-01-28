<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Definitions;

use Railt\Reflection\Contracts\Defintions\Directive\HasArguments;

/**
 * Interface DirectiveDefinition
 */
interface DirectiveDefinition extends TypeDefinition, HasArguments
{
    /**
     * @return iterable|string[]
     */
    public function getLocations(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasLocation(string $name): bool;

    /**
     * Returns the number of locations that the directive contains.
     *
     * @return int
     */
    public function getNumberOfLocations(): int;

    /**
     * @param null|TypeDefinition $type
     * @return bool
     */
    public function isAllowedFor(?TypeDefinition $type): bool;

    /**
     * @return bool
     */
    public function isAllowedForQueries(): bool;

    /**
     * @return bool
     */
    public function isAllowedForSchemaDefinitions(): bool;
}
