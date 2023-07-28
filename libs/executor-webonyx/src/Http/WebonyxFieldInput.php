<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Http;

use GraphQL\Type\Definition\ResolveInfo;
use Railt\Contracts\Http\InputInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

/**
 * @template-implements InputInterface<FieldDefinition>
 */
final class WebonyxFieldInput implements InputInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly FieldDefinition $field,
        private readonly ResolveInfo $info,
        private readonly array $arguments = [],
    ) {
    }

    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }

    public function getArgument(string $name, mixed $default = null): mixed
    {
        if (\array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }

        return $default;
    }

    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    public function getPath(): array
    {
        return $this->info->path;
    }

    public function getPathAsString(string $delimiter = self::DEFAULT_PATH_DELIMITER): string
    {
        return \implode($delimiter, $this->info->path);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getName(): string
    {
        return $this->field->getName();
    }

    public function getAlias(): ?string
    {
        /** @var non-empty-string $alias */
        $alias = \end($this->info->path);

        if ($alias === $this->field->getName()) {
            return null;
        }

        return $alias;
    }

    public function getDefinition(): FieldDefinition
    {
        return $this->field;
    }

    public function getResolveInfo(): ResolveInfo
    {
        return $this->info;
    }
}
