<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use Illuminate\Support\Str;
use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Contracts\Types\TypeInterface;
use Serafim\Railgun\Contracts\TypesRegistryInterface;

/**
 * Class TypesRegistry
 * @package Serafim\Railgun\Adapters\Webonyx
 */
class TypesRegistry
{
    /**
     * @var TypesRegistryInterface
     */
    private $registry;

    /**
     * @var array|Type[]
     */
    private $types = [];

    /**
     * @var \Closure
     */
    private $resolver;

    /**
     * TypesRegistry constructor.
     * @param TypesRegistryInterface $registry
     * @param \Closure $resolver
     */
    public function __construct(TypesRegistryInterface $registry, \Closure $resolver)
    {
        $this->registry = $registry;
        $this->resolver = $resolver;

        $this->bootInternalTypes();
    }

    /**
     * @param TypeInterface $type
     * @return Type
     */
    private function resolve(TypeInterface $type): Type
    {
        return ($this->resolver)($type);
    }

    /**
     * @param string $name
     * @return Type
     */
    public function get(string $name): Type
    {
        $type = $this->registry->get($name);

        if ($this->isDefined($type->getName())) {
            return $this->getDefined($type->getName());
        }

        if (! $this->isDefined($name = get_class($type))) {
            return $this->define($name, $this->resolve($type));
        }

        return $this->getDefined($name);
    }

    /**
     * @param string $name
     * @param Type $type
     * @return Type
     */
    private function define(string $name, Type $type): Type
    {
        return $this->types[$name] = $type;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isDefined(string $name): bool
    {
        return array_key_exists(Str::lower($name), $this->types);
    }

    /**
     * @param string $name
     * @return Type
     */
    private function getDefined(string $name): Type
    {
        return $this->types[Str::lower($name)];
    }

    /**
     * @return void
     */
    private function bootInternalTypes(): void
    {
        foreach (Type::getInternalTypes() as $name => $type) {
            $this->types[Str::lower($name)] = $type;
        }
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return array_values($this->types);
    }
}
