<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Reference;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Interface TypeReferenceInterface
 */
interface TypeReferenceInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param DefinitionInterface $context
     * @return NamedTypeInterface
     */
    public function getType(DefinitionInterface $context): NamedTypeInterface;
}
