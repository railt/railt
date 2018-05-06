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
use Railt\Routing\Route\Relation;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class Route
 */
class Route
{
    public const PATH_DELIMITER = '.';

    /**
     * @var FieldDefinition
     */
    protected $field;

    /**
     * @var \Closure|null
     */
    protected $action;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array|string[]
     */
    protected $operations = [];

    /**
     * @var array|Relation[]
     */
    private $relations = [];

    /**
     * @var TypeDefinition|null
     */
    private $type;

    /**
     * Route constructor.
     * @param ContainerInterface $container
     * @param FieldDefinition $field
     */
    public function __construct(ContainerInterface $container, FieldDefinition $field)
    {
        $this->field      = $field;
        $this->container  = $container;
    }

    /**
     * @param string ...$operations
     * @return Route
     */
    public function on(string ...$operations): self
    {
        $this->operations = \array_merge($this->operations, $operations);

        return $this;
    }

    /**
     * @param TypeDefinition $definition
     * @return Route
     */
    public function wants(TypeDefinition $definition): self
    {
        $this->type = $definition;

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
    public function getField(): FieldDefinition
    {
        return $this->field;
    }

    /**
     * @return null|TypeDefinition
     */
    public function getType(): ?TypeDefinition
    {
        return $this->type;
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
            'field'      => (string)$this->field->getParent() . (string)$this->field,
            'type'       => $this->type,
            'relations'  => $this->relations,
            'operations' => $this->operations,
        ];
    }
}
