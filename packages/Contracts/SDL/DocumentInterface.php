<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\SDL;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Interface DocumentInterface
 */
interface DocumentInterface
{
    /**
     * @return iterable|NamedTypeInterface[]
     */
    public function getTypeMap(): iterable;

    /**
     * @return iterable|DirectiveInterface[]
     */
    public function getDirectives(): iterable;

    /**
     * @return SchemaInterface|null
     */
    public function getSchema(): ?SchemaInterface;
}
