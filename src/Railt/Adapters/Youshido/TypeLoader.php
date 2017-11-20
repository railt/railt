<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Youshido;

use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Youshido\Builders\ArgumentBuilder;
use Railt\Adapters\Youshido\Builders\FieldBuilder;
use Railt\Adapters\Youshido\Builders\ObjectBuilder;
use Railt\Adapters\Youshido\Builders\ScalarBuilder;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class TypeLoader
 */
class TypeLoader
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array|string[]|TypeInterface[]
     */
    private $mappings = [
        // Types
        ObjectDefinition::class   => ObjectBuilder::class,
        ScalarDefinition::class   => ScalarBuilder::class,

        // Dependent type
        FieldDefinition::class    => FieldBuilder::class,
        ArgumentDefinition::class => ArgumentBuilder::class,
    ];

    /**
     * TypeLoader constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param TypeDefinition $type
     * @return TypeInterface|FieldInterface|array
     * @throws \InvalidArgumentException
     */
    public function load(TypeDefinition $type)
    {
        /**
         * If dynamically build type
         */
        if ($type instanceof DependentDefinition) {
            return $this->build($type);
        }

        /**
         * Otherwise check already loaded type
         */
        if (! $this->loaded($type)) {
            $this->types[$type->getName()] = $this->build($type);
        }

        /**
         * Just return it
         */
        return $this->get($type);
    }

    /**
     * @param TypeDefinition $type
     * @return TypeInterface|FieldInterface
     * @throws \InvalidArgumentException
     */
    private function build(TypeDefinition $type)
    {
        foreach ($this->mappings as $definition => $builder) {
            if ($type instanceof $definition) {
                return new $builder($this->adapter, $type);
            }
        }

        $error = \sprintf('It is impossible to define a builder for type %s', $type);
        throw new \InvalidArgumentException($error);
    }

    /**
     * @param TypeDefinition $type
     * @return bool
     */
    private function loaded(TypeDefinition $type): bool
    {
        return \array_key_exists($type->getName(), $this->types);
    }

    /**
     * @param TypeDefinition $type
     * @return TypeInterface|FieldInterface
     */
    private function get(TypeDefinition $type)
    {
        return $this->types[$type->getName()];
    }
}
