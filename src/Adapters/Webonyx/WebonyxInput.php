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
use Railt\Foundation\Events\ArgumentResolving;
use Railt\Http\InputInterface;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\Argument\HasArguments;
use Railt\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;

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
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * WebonyxInput constructor.
     * @param Dispatcher $dispatcher
     * @param FieldDefinition $field
     * @param ResolveInfo $info
     * @param array $arguments
     * @throws \InvalidArgumentException
     */
    public function __construct(Dispatcher $dispatcher, FieldDefinition $field, ResolveInfo $info, array $arguments = [])
    {
        $this->dispatcher = $dispatcher;
        $this->info       = $info;
        $this->field      = $field;
        $this->arguments  = $this->resolveArguments($field, $arguments);
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

        /** @var ArgumentDefinition $argument */
        foreach ($reflection->getArguments() as $argument) {
            $result[$argument->getName()] = $this->resolveArgument($argument, $input);
        }

        return $result;
    }

    /**
     * @param ArgumentDefinition $argument
     * @param array $input
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function resolveArgument(ArgumentDefinition $argument, array $input)
    {
        $name = $argument->getName();

        if ($this->isMissingArgument($argument, $input)) {
            $parent = $argument->getParent();

            $message = \sprintf('Argument %s is required for %s', $argument, $parent);

            throw new \InvalidArgumentException($message);
        }

        if ($this->isPassedArgument($argument, $input)) {
            return $this->formatArgument($argument, $input[$name]);
        }

        return $this->formatArgument($argument, $argument->getDefaultValue());
    }

    /**
     * @param ArgumentDefinition $argument
     * @param array $input
     * @return bool
     */
    private function isPassedArgument(ArgumentDefinition $argument, array $input): bool
    {
        return \array_key_exists($argument->getName(), $input);
    }

    /**
     * @param ArgumentDefinition $argument
     * @param $value
     * @return mixed
     */
    private function formatArgument(ArgumentDefinition $argument, $value)
    {
        $resolving = new ArgumentResolving($argument, $value);

        $this->dispatcher->dispatch(ArgumentResolving::class, $resolving);

        return $resolving->getValue();
    }

    /**
     * @param ArgumentDefinition $argument
     * @param array $input
     * @return bool
     */
    private function isMissingArgument(ArgumentDefinition $argument, array $input): bool
    {
        $passed = $this->isPassedArgument($argument, $input);

        //  No passed value   No default value                Required (not null)
        return ! $passed && ! $argument->hasDefaultValue() && $argument->isNonNull();
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
        $this->parentValue    = $parent;
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
     */
    public function hasAlias(): bool
    {
        return $this->getAlias() !== $this->getFieldName();
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->info->path[\count($this->info->path) - 1];
    }
}
