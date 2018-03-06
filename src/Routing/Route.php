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
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Routing\Contracts\RegistryInterface;

/**
 * Class Route
 */
class Route
{
    private const PARENT_ARGUMENT_NAME = 'parent';

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
     * @var RegistryInterface
     */
    protected $registry;

    /**
     * @var string
     */
    protected $field = self::FIELD_ANY;

    /**
     * @var array|string[]
     */
    protected $operations = [];

    /**
     * @var String
     */
    protected $parent;

    /**
     * @var String
     */
    protected $child;

    /**
     * @var mixed|null
     */
    protected $parentValue;

    /**
     * Route constructor.
     * @param ContainerInterface $container
     * @param FieldDefinition $type
     */
    public function __construct(ContainerInterface $container, FieldDefinition $type)
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
     * @param InputInterface $input
     * @param mixed $parent
     * @param array $params
     * @return mixed
     */
    public function call(InputInterface $input, $parent, array $params = [])
    {
        $this->parentValue = $parent;

        if ($this->isSingularInvocation()) {
            return $this->invokeSingularAction($input, $parent, $params);
        }

        return $this->invokeDividedAction($input, $params);
    }

    /**
     * @return bool
     */
    private function isSingularInvocation(): bool
    {
        return $this->parent && $this->child;
    }

    /**
     * @param InputInterface $input
     * @param array $parent
     * @param array $params
     * @return array
     */
    private function invokeSingularAction(InputInterface $input, ?array $parent, array $params): array
    {
        $this->prepareSingularInvocation($input, $params);

        /** @var iterable $stored */
        $stored = $this->registry->get($input->getPath());

        return \iterator_to_array($this->join($stored, $parent));
    }

    /**
     * @param InputInterface $input
     * @param array $params
     */
    private function prepareSingularInvocation(InputInterface $input, array $params): void
    {
        if (! $this->isFirstInvocation($input->getPath())) {
            $this->parentValue = $this->registry->get($this->getParentPath($input->getPath()));

            $this->invokeDividedAction($input, $params);
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

        \array_pop($parts);

        return \implode(self::PATH_DELIMITER, $parts);
    }

    /**
     * @param InputInterface $input
     * @param array $params
     * @return mixed
     */
    private function invokeDividedAction(InputInterface $input, array $params)
    {
        return $this->registry->set($input->getPath(), $this->callAction($input, $params));
    }

    /**
     * @param InputInterface $input
     * @param array $params
     * @return mixed
     */
    private function callAction(InputInterface $input, array $params)
    {
        $params = \array_merge($params, [self::PARENT_ARGUMENT_NAME => $this->parentValue]);
        $input->updateParentValue($this->parentValue);

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
     * @param array $data
     * @param array|null $parent
     * @return \Traversable|array[]
     */
    private function join(array $data, ?array $parent): \Traversable
    {
        foreach ($data as $child) {
            if (! \is_array($child) || ! \array_key_exists($this->child, $child)) {
                continue;
            }

            if (! \is_array($parent) || ! \array_key_exists($this->parent, $parent)) {
                continue;
            }

            if ($child[$this->child] === $parent[$this->parent]) {
                yield $child;
            }
        }
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'relation'   => ['parent' => $this->parent, 'child' => $this->child],
            'type'       => $this->type->getParent() . $this->type,
            'field'      => $this->field,
            'operations' => $this->operations,
        ];
    }
}
