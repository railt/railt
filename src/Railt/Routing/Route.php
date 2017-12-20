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
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class Route
 */
class Route
{
    private const FIELD_ANY = '*';

    /**
     * @var TypeDefinition
     */
    private $type;

    /**
     * @var \Closure|null
     */
    private $action;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $field = self::FIELD_ANY;

    /**
     * @var array|string[]
     */
    private $operations = [];

    /**
     * Route constructor.
     * @param ContainerInterface $container
     * @param TypeDefinition $type
     */
    public function __construct(ContainerInterface $container, TypeDefinition $type)
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
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
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
     * @return \Closure
     */
    public function getAction(): \Closure
    {
        return $this->action ?? function (): void {
        };
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function call(array $params = [])
    {
        return $this->container->call($this->getAction(), $params);
    }
}
