<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Containers\HasArguments;

/**
 * Interface DirectiveType
 */
interface DirectiveType extends HasArguments, NamedTypeInterface
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
     * @param TypeInterface $type
     * @return bool
     */
    public function isAllowedFor(TypeInterface $type): bool;

    /**
     * @return bool
     */
    public function isAllowedForQueries(): bool;

    /**
     * @return bool
     */
    public function isAllowedForSchemaDefinitions(): bool;
}
