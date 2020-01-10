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
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class TypeReference
 */
class TypeReference implements TypeReferenceInterface
{
    /**
     * @var SchemaInterface
     */
    protected SchemaInterface $schema;

    /**
     * @var string
     */
    protected string $name;

    /**
     * TypeReference constructor.
     *
     * @param SchemaInterface $schema
     * @param string $name
     */
    public function __construct(SchemaInterface $schema, string $name)
    {
        $this->schema = $schema;
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
        return $this->schema->getType($this->name);
    }
}
