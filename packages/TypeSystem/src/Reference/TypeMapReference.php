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
 * Class TypeMapReference
 */
class TypeMapReference implements TypeReferenceInterface
{
    /**
     * @var TypeMap
     */
    protected TypeMap $typeMap;

    /**
     * @var string
     */
    protected string $name;

    /**
     * TypeMapReference constructor.
     *
     * @param TypeMap $typeMap
     * @param string $name
     */
    public function __construct(TypeMap $typeMap, string $name)
    {
        $this->typeMap = $typeMap;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param DefinitionInterface $context
     * @return NamedTypeInterface
     */
    public function getType(DefinitionInterface $context): NamedTypeInterface
    {
        return $this->typeMap[$this->name];
    }
}
