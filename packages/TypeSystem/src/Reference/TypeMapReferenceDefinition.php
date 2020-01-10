<?php

/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Reference;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Collection\TypeMap;

/**
 * Class TypeMapReferenceDefinition
 */
class TypeMapReferenceDefinition implements TypeReferenceInterface
{
    /**
     * @var NamedTypeInterface
     */
    private NamedTypeInterface $type;

    /**
     * TypeMapReferenceDefinition constructor.
     *
     * @param TypeMap $typeMap
     * @param NamedTypeInterface $type
     */
    public function __construct(TypeMap $typeMap, NamedTypeInterface $type)
    {
        $typeMap[$type->getName()] = $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->type->getName();
    }

    /**
     * @param DefinitionInterface $context
     * @return NamedTypeInterface
     */
    public function getType(DefinitionInterface $context): NamedTypeInterface
    {
        return $this->type;
    }
}
