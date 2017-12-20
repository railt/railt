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
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Routing\Contracts\InputInterface;
use Railt\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;

/**
 * Class Input
 */
class Input implements InputInterface
{
    public const DEPTH_DELIMITER = '.';

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
     * @var ObjectDefinition|TypeDefinition
     */
    private $type;

    /**
     * Input constructor.
     * @param ObjectDefinition $type
     * @param ResolveInfo $info
     * @param array $arguments
     */
    public function __construct(ObjectDefinition $type, ResolveInfo $info, array $arguments = [])
    {
        $this->info = $info;
        $this->type = $type;
        $this->arguments = $arguments;
    }

    /**
     * @param HasArguments $reflection
     * @param array $input
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function resolveArguments(HasArguments $reflection, array $input = []): array
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
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getQueryType(): string
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
            $path = $this->info->path;
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
