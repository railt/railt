<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Route\Relation;

/**
 * Class Route
 */
class Route
{
    public const FIELD_ANY      = '*';
    public const PATH_DELIMITER = '.';

    /**
     * @var FieldDefinition
     */
    protected $type;

    /**
     * @var \Closure|null
     */
    protected $action;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $field = self::FIELD_ANY;

    /**
     * @var array|string[]
     */
    protected $operations = [];

    /**
     * @var array|Relation[]
     */
    private $relations = [];

    /**
     * Route constructor.
     * @param ContainerInterface $container
     * @param FieldDefinition $type
     */
    public function __construct(ContainerInterface $container, FieldDefinition $type)
    {
        $this->type      = $type;
        $this->container = $container;
    }

    /**
     * @param string $field
     * @return Route
     */
    public function when(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @param string[] ...$operations
     * @return Route
     */
    public function on(string ...$operations): self
    {
        $this->operations = \array_merge($this->operations, $operations);

        return $this;
    }

    /**
     * @param string $parent
     * @param string $child
     * @return Route
     */
    public function relation(string $child, string $parent = Relation::PARENT_DEFAULT_FIELD): self
    {
        $this->relations[] = new Relation($child, $parent);

        return $this;
    }

    /**
     * @param \Closure $action
     * @return Route
     */
    public function then(\Closure $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function matchOperation(string $name): bool
    {
        if ($this->operations === []) {
            return true;
        }

        return \in_array(\mb_strtolower($name), $this->operations, true);
    }

    /**
     * @return FieldDefinition
     */
    public function getTypeDefinition(): FieldDefinition
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->field;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function call(array $params = [])
    {
        return $this->container->call($this->getAction(), $params);
    }

    /**
     * @return \Closure
     */
    public function getAction(): \Closure
    {
        return $this->action ??
            function (): void {
                // Otherwise do nothing
            };
    }

    /**
     * @return bool
     */
    public function hasRelations(): bool
    {
        return \count($this->relations) > 0;
    }

    /**
     * @return iterable|Relation[]
     */
    public function getRelations(): iterable
    {
        return $this->relations;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'relations'  => $this->relations,
            'type'       => (string)$this->type->getParent() . (string)$this->type,
            'field'      => $this->field,
            'operations' => $this->operations,
        ];
    }
}
