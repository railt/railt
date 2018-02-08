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
use Railt\Routing\Contracts\InputInterface;
use Railt\Routing\Contracts\RegistryInterface;

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
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var string
     */
    private $field = self::FIELD_ANY;

    /**
     * @var array|string[]
     */
    private $operations = [];

    /**
     * @var String
     */
    private $parent;

    /**
     * @var String
     */
    private $child;

    /**
     * Route constructor.
     * @param ContainerInterface $container
     * @param TypeDefinition $type
     */
    public function __construct(ContainerInterface $container, TypeDefinition $type)
    {
        $this->type      = $type;
        $this->container = $container;
        $this->registry  = $container->make(RegistryInterface::class);
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
     * @return void
     */
    public function relation(string $parent, string $child): void
    {
        $this->parent = $parent;
        $this->child  = $child;
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
        $path = $params[InputInterface::class]->getPath();

        if ($this->isOneCallPerParent()) {
            return $this->oneCallPerParent($path, $params);
        }

        return $this->oneCallPerItem($path, $params);
    }

    /**
     * @return bool
     */
    private function isOneCallPerParent(): bool
    {
        return $this->parent && $this->child;
    }

    /**
     * @param string $path
     * @param array $params
     * @return mixed
     */
    private function oneCallPerItem(string $path, array $params)
    {
        $this->registry->set($path, $this->container->call($this->getAction(), $params));

        return $this->registry->get($path);
    }

    /**
     * @param $path
     * @param $params
     * @return array
     */
    private function oneCallPerParent($path, $params)
    {
        if (! $this->registry->has($path)) {
            $this->oneCallPerItem($path, \array_merge($params, [
                'parent' => $this->registry->get($this->getParentPath($path)),
            ]));
        }

        $data = [];
        foreach ($this->registry->get($path) as $child) {
            if ($child[$this->child] === $params['parent'][$this->parent]) {
                $data[] = $child;
            }
        }

        return $data;
    }

    /**
     * @param string $path
     * @return string
     */
    private function getParentPath(string $path): string
    {
        $parts = \explode('.', $path);
        \array_pop($parts);

        return \implode('.', $parts);
    }
}
