<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\SDL\Runtime\ExecutionInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Interface DocumentInterface
 */
interface DocumentInterface
{
    /**
     * @return iterable|NamedTypeInterface[]
     */
    public function getTypes(): iterable;

    /**
     * @param string $name
     * @return NamedTypeInterface|null
     */
    public function getType(string $name): ?NamedTypeInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool;

    /**
     * @return iterable|DirectiveInterface[]
     */
    public function getDirectives(): iterable;

    /**
     * @param string $name
     * @return DirectiveInterface|null
     */
    public function getDirective(string $name): ?DirectiveInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool;

    /**
     * @return SchemaInterface|null
     */
    public function getSchema(): ?SchemaInterface;

    /**
     * @return iterable|ExecutionInterface[]
     */
    public function getExecutions(): iterable;
}
