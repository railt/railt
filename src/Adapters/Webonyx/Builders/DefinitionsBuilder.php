<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builders;

use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Adapters\Webonyx\BuilderInterface;
use Serafim\Railgun\Contracts\Definitions\DefinitionInterface;
use Serafim\Railgun\Contracts\Definitions\TypeDefinitionInterface;

/**
 * Class DefinitionsBuilder
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
class DefinitionsBuilder
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * DefinitionsBuilder constructor.
     * @param BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param DefinitionInterface $definition
     * @return Type
     * @throws \InvalidArgumentException
     */
    public function build(DefinitionInterface $definition): Type
    {
        switch (true) {
            case $definition instanceof TypeDefinitionInterface:
                return $this->makeTypeDefinition($definition);
        }

        throw new \InvalidArgumentException('Invalid type definition for: ' . get_class($definition));
    }

    /**
     * @param TypeDefinitionInterface $definition
     * @return Type
     * @throws \InvalidArgumentException
     */
    private function makeTypeDefinition(TypeDefinitionInterface $definition): Type
    {
        $type = $this->builder->type($definition->getTypeName());

        if ($definition->isList()) {
            $type = Type::listOf($type);
        }

        return $definition->isNullable() ? $type : Type::nonNull($type);
    }
}
