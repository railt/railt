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
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Routing\Contracts\RegistryInterface;

/**
 * Class Route
 */
class Route
{
    public const FIELD_ANY = '*';
    public const PATH_DELIMITER = '.';

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
     * @param array $params
     * @return mixed
     */
    public function call(array $params = [])
    {
        $path = $params[InputInterface::class]->getPath();

        if ($this->isSingularInvocation()) {
            return $this->invokeSingularAction($path, $params);
        }

        return $this->invokeDividedAction($path, $params);
    }

    /**
     * @return bool
     */
    private function isSingularInvocation(): bool
    {
        return $this->parent && $this->child;
    }

    /**
     * @param string $path
     * @param array $params
     * @return array
     */
    private function invokeSingularAction(string $path, array $params): array
    {
        $this->prepareSingularInvocation($path, $params);

        $data = [];

        /** @var iterable $stored */
        $stored = $this->registry->get($path);

        foreach ($stored as $child) {
            if ($child[$this->child] === $params['parent'][$this->parent]) {
                $data[] = $child;
            }
        }

        return $data;
    }

    /**
     * @param string $path
     * @param array $params
     */
    private function prepareSingularInvocation(string $path, array $params): void
    {
        if (! $this->isFirstInvocation($path)) {
            $params = \array_merge($params, [
                'parent' => $this->registry->get($this->getParentPath($path)),
            ]);

            $this->invokeDividedAction($path, $params);
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isFirstInvocation(string $path): bool
    {
        return $this->registry->has($path);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getParentPath(string $path): string
    {
        $parts = \explode(self::PATH_DELIMITER, $path);

        return \implode(self::PATH_DELIMITER, \array_slice($parts, -1));
    }

    /**
     * @param string $path
     * @param array $params
     * @return mixed
     */
    private function invokeDividedAction(string $path, array $params)
    {
        return $this->registry->set($path, $this->container->call($this->getAction(), $params));
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
}
