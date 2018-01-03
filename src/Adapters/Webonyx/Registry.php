<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\Type;
use Railt\Adapters\Webonyx\Builders\TypeBuilder;
use Railt\Container\ContainerInterface;
use Railt\Reflection\Contracts\Definitions;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class Registry
 */
class Registry
{
    private const BUILDER_MAPPINGS = [
        Definitions\ObjectDefinition::class    => Builders\ObjectBuilder::class,
        Definitions\InterfaceDefinition::class => Builders\InterfaceBuilder::class,
        Definitions\DirectiveDefinition::class => Builders\DirectiveBuilder::class,
        Definitions\ScalarDefinition::class    => Builders\ScalarBuilder::class,
    ];

    /**
     * @var array|Type[]
     */
    private $types = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Registry constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param TypeDefinition $definition
     * @return Type|Directive
     * @throws \InvalidArgumentException
     */
    public function get(TypeDefinition $definition)
    {
        $name = $definition->getName();

        if (! \array_key_exists($name, $this->types)) {
            $this->types[$name] = $this->build($definition);
        }

        return $this->types[$name];
    }

    /**
     * @param TypeDefinition $definition
     * @return Type|Directive
     * @throws \InvalidArgumentException
     */
    private function build(TypeDefinition $definition)
    {
        return $this->getBuilder($definition)->build();
    }

    /**
     * @param TypeDefinition $definition
     * @return TypeBuilder
     * @throws \InvalidArgumentException
     */
    private function getBuilder(TypeDefinition $definition): TypeBuilder
    {
        /** @var TypeBuilder $builder */
        $builder = $this->getMapping($definition);

        return new $builder($definition, $this);
    }

    /**
     * @param TypeDefinition $definition
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getMapping(TypeDefinition $definition): string
    {
        foreach (self::BUILDER_MAPPINGS as $contract => $builder) {
            if ($definition instanceof $contract) {
                return $builder;
            }
        }

        $error = 'Can not find an allowable Builder for the %s';
        throw new \InvalidArgumentException(\sprintf($error, $definition));
    }
}
