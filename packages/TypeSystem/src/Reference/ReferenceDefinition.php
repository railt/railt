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
use Railt\TypeSystem\Schema;

/**
 * Class ReferenceDefinition
 */
class ReferenceDefinition implements TypeReferenceInterface
{
    /**
     * @var NamedTypeInterface
     */
    protected NamedTypeInterface $type;

    /**
     * ReferenceDefinition constructor.
     *
     * @param Schema $schema
     * @param NamedTypeInterface $type
     */
    public function __construct(Schema $schema, NamedTypeInterface $type)
    {
        $schema->addType($this->type = $type);
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
