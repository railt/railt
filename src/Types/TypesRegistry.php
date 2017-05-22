<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

use Illuminate\Support\Str;
use Serafim\Railgun\Contracts\SchemaInterface;
use Serafim\Railgun\Contracts\Types\TypeInterface;
use Serafim\Railgun\Contracts\TypesRegistryInterface;
use Serafim\Railgun\Contracts\Types\InternalTypeInterface;

/**
 * Class TypesRegistry
 * @package Serafim\Railgun\Types
 */
class TypesRegistry implements TypesRegistryInterface
{
    public const INTERNAL_TYPE_ID = 'id';
    public const INTERNAL_TYPE_INT = 'int';
    public const INTERNAL_TYPE_FLOAT = 'float';
    public const INTERNAL_TYPE_STRING = 'string';
    public const INTERNAL_TYPE_BOOLEAN = 'boolean';

    private const INTERNAL_TYPES = [
        self::INTERNAL_TYPE_ID,
        self::INTERNAL_TYPE_INT,
        self::INTERNAL_TYPE_FLOAT,
        self::INTERNAL_TYPE_STRING,
        self::INTERNAL_TYPE_BOOLEAN,
    ];

    /**
     * @var array
     */
    private const INTERNAL_TYPE_ALIASES = [
        // ID
        'id'      => self::INTERNAL_TYPE_ID,

        // Int
        'int'     => self::INTERNAL_TYPE_INT,
        'number'  => self::INTERNAL_TYPE_INT,
        'integer' => self::INTERNAL_TYPE_INT,

        // Float
        'real'    => self::INTERNAL_TYPE_FLOAT,
        'float'   => self::INTERNAL_TYPE_FLOAT,
        'double'  => self::INTERNAL_TYPE_FLOAT,

        // String
        'str'     => self::INTERNAL_TYPE_STRING,
        'string'  => self::INTERNAL_TYPE_STRING,

        // Boolean
        'bool'    => self::INTERNAL_TYPE_BOOLEAN,
        'boolean' => self::INTERNAL_TYPE_BOOLEAN,
    ];

    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array
     */
    private $aliases = [];

    /**
     * @var \Closure
     */
    private $onCreateType;

    /**
     * @var \Closure
     */
    private $onCreateSchema;

    /**
     * @var array|SchemaInterface
     */
    private $schemas = [];

    /**
     * TypesRegistry constructor.
     * @param \Closure|null $onCreateType
     * @param \Closure|null $onCreateSchema
     * @throws \InvalidArgumentException
     */
    public function __construct(\Closure $onCreateType = null, \Closure $onCreateSchema = null)
    {
        $this->bootInternalTypes();

        $this->onCreateType = $onCreateType ?? function(string $class) {
            return new $class;
        };

        $this->onCreateSchema = $onCreateSchema ?? function(string $class) {
            return new $class;
        };
    }

    /**
     * @param string|SchemaInterface $class
     * @return SchemaInterface
     */
    public function schema(string $class): SchemaInterface
    {
        if (!array_key_exists($class, $this->schemas)) {
            $this->schemas[$class] = ($this->onCreateSchema)($class);
        }

        return $this->schemas[$class];
    }


    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    private function bootInternalTypes(): void
    {
        foreach (self::INTERNAL_TYPES as $typeName) {
            $this->types[$typeName] = new InternalType($typeName);
        }

        foreach (self::INTERNAL_TYPE_ALIASES as $alias => $typeName) {
            $this->alias($typeName, $alias);
        }
    }

    /**
     * @param TypeInterface $type
     * @param \string[] ...$aliases
     * @return TypesRegistryInterface
     * @throws \InvalidArgumentException
     * @internal param string $class
     */
    public function add(TypeInterface $type, string ...$aliases): TypesRegistryInterface
    {
        $name = $this->getName($type);

        $this->types[$name] = $type;

        $this->alias($name, $this->getAlias($type), ...$aliases);

        return $this;
    }

    /**
     * @param TypeInterface $type
     * @return string
     */
    private function getName(TypeInterface $type): string
    {
        return get_class($type);
    }

    /**
     * @param TypeInterface $type
     * @return string
     */
    private function getAlias(TypeInterface $type): string
    {
        return $type->getName();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isInternal(string $name): bool
    {
        return array_key_exists(Str::lower($name), self::INTERNAL_TYPE_ALIASES);
    }

    /**
     * @param string $name
     * @param bool $withInternal
     * @return TypeInterface
     * @throws \InvalidArgumentException
     */
    public function get(string $name, bool $withInternal = true): TypeInterface
    {
        $result = null;

        if (array_key_exists($name, $this->types)) {
            $result = $this->types[$name];

        } elseif ($this->isAlias($name)) {
            $result = $this->get($this->aliases[$name]);

        } elseif (class_exists($name)) {
            $result = $this->add($this->create($name))->get($name);
        }

        if (!$withInternal && $result instanceof InternalTypeInterface) {
            $result = null;
        }

        if ($result === null) {
            throw new \InvalidArgumentException('Invalid type ' . $name);
        }

        return $result;
    }

    /**
     * @param string $class
     * @return TypeInterface
     * @throws \InvalidArgumentException
     */
    private function create(string $class): TypeInterface
    {
        $result = ($this->onCreateType)($class);

        if (!($result instanceof TypeInterface)) {
            throw new \InvalidArgumentException('Incompatible GraphQL type ' . $class);
        }

        return $result;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isAlias(string $name): bool
    {
        return array_key_exists($name, $this->aliases);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->types) || $this->isAlias($name);
    }

    /**
     * @param string $original
     * @param \string[] ...$aliases
     * @return TypesRegistryInterface
     * @throws \InvalidArgumentException
     */
    public function alias(string $original, string ...$aliases): TypesRegistryInterface
    {
        $aliases = array_filter($aliases, function(string $alias) use ($original) {
            return $alias !== $original;
        });

        if ($this->isAlias($original)) {
            return $this->alias($this->aliases[$original], ...$aliases);
        }

        foreach ($aliases as $alias) {
            $this->aliases[$alias] = $original;
        }

        return $this;
    }

    /**
     * @return iterable
     */
    public function all(): iterable
    {
        foreach ($this->types as $name => $value) {
            if (!($value instanceof InternalTypeInterface)) {
                yield $value;
            }
        }
    }
}
