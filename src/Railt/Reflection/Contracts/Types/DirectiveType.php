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
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;

/**
 * Interface DirectiveType
 */
interface DirectiveType extends HasArguments, NamedTypeDefinition
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

    /**
     * @internal Directive type can not contain other directives but this method is allowed for all types.
     *
     * @param string $name
     * @return null|DirectiveInvocation
     */
    public function getDirective(string $name): ?DirectiveInvocation;

    /**
     * @internal Directive type can not contain other directives but this method is allowed for all types.
     *
     * @return iterable|DirectiveInvocation[]
     */
    public function getDirectives(): iterable;

    /**
     * @internal Directive type can not contain other directives but this method is allowed for all types.
     *
     * @return int
     */
    public function getNumberOfDirectives(): int;

    /**
     * @internal Directive type can not contain other directives but this method is allowed for all types.
     *
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;
}
