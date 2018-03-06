<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Type\Definition\ResolveInfo;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;

/**
 * Class WebonyxInput
 */
class WebonyxInput implements InputInterface
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @var ResolveInfo
     */
    private $info;

    /**
     * @var null|string
     */
    private $path;

    /**
     * @var FieldDefinition|TypeDefinition
     */
    private $field;

    /**
     * @var mixed
     */
    private $parentValue;

    /**
     * @var mixed
     */
    private $parentResponse;

    /**
     * Input constructor.
     * @param FieldDefinition $field
     * @param ResolveInfo $info
     * @param array $arguments
     * @throws \InvalidArgumentException
     */
    public function __construct(FieldDefinition $field, ResolveInfo $info, array $arguments = [])
    {
        $this->info      = $info;
        $this->field     = $field;
        $this->arguments = $this->resolveArguments($field, $arguments);
    }

    /**
     * @param HasArguments $reflection
     * @param array $input
     * @return array
     * @throws \InvalidArgumentException
     */
    private function resolveArguments(HasArguments $reflection, array $input = []): array
    {
        $result = [];

        /** @var ArgumentDefinition $default */
        foreach ($reflection->getArguments() as $default) {
            $name = $default->getName();

            if (
                ! \array_key_exists($name, $input) && // Empty argument
                ! $default->hasDefaultValue() &&      // And has no default value
                $default->isNonNull()                 // And required
            ) {
                $message = \sprintf('Argument %s required for field %s',
                    $name,
                    $default->getParent()->getName()
                );
                throw new \InvalidArgumentException($message);
            }

            if (\array_key_exists($name, $input)) {
                $result[$name] = $input[$name];
            } elseif ($default->hasDefaultValue()) {
                $result[$name] = $default->getDefaultValue();
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parentValue;
    }

    /**
     * @return mixed
     */
    public function getParentResponse()
    {
        return $this->parentResponse;
    }

    /**
     * @param mixed $parent
     * @param mixed $parentResponse
     */
    public function updateParent($parent, $parentResponse): void
    {
        $this->parentValue = $parent;
        $this->parentResponse = $parentResponse;
    }

    /**
     * @return FieldDefinition
     */
    public function getFieldDefinition(): FieldDefinition
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->info->operation->operation;
    }

    /**
     * @return ResolveInfo
     */
    public function getResolveInfo(): ResolveInfo
    {
        return $this->info;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $argument
     * @param null $default
     * @return mixed|null
     */
    public function get(string $argument, $default = null)
    {
        return $this->arguments[$argument] ?? $default;
    }

    /**
     * @param string $argument
     * @return bool
     */
    public function has(string $argument): bool
    {
        return \array_key_exists($argument, $this->arguments);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if ($this->path === null) {
            $path                    = $this->info->path;
            $path[\count($path) - 1] = $this->getFieldName();

            // Remove array indexes
            $path = \array_filter($path, '\\is_string');

            // Remove empty values
            $path = \array_filter($path, '\\trim');

            $this->path = \implode(self::DEPTH_DELIMITER, $path);
        }

        return $this->path;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->info->fieldName;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'path'      => $this->path,
            'arguments' => $this->arguments,
        ];
    }

    /**
     * @return bool
     * @throws \LogicException
     */
    public function hasAlias(): bool
    {
        return $this->getAlias() !== $this->getFieldName();
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function getAlias(): string
    {
        return $this->info->path[\count($this->info->path) - 1];
    }
}
