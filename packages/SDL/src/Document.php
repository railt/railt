<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Contracts\SDL\DocumentInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class Document
 */
final class Document implements DocumentInterface
{
    /**
     * @var array|NamedTypeInterface[]
     */
    private array $typeMap = [];

    /**
     * @var array|DirectiveInterface[]
     */
    private array $directives = [];

    /**
     * @var SchemaInterface|null
     */
    private ?SchemaInterface $schema = null;

    /**
     * {@inheritDoc}
     */
    public function getType(string $name): ?NamedTypeInterface
    {
        return $this->typeMap[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasType(string $name): bool
    {
        return isset($this->typeMap[$name]);
    }

    /**
     * @param NamedTypeInterface $type
     * @return void
     */
    public function addType(NamedTypeInterface $type): void
    {
        $this->typeMap[$type->getName()] = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirective(string $name): ?DirectiveInterface
    {
        return $this->directives[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDirective(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    /**
     * @param DirectiveInterface $directive
     * @return void
     */
    public function addDirective(DirectiveInterface $directive): void
    {
        $this->directives[$directive->getName()] = $directive;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): iterable
    {
        return $this->typeMap;
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectives(): iterable
    {
        return $this->directives;
    }

    /**
     * {@inheritDoc}
     */
    public function getSchema(): ?SchemaInterface
    {
        return $this->schema;
    }

    /**
     * @param SchemaInterface|null $schema
     * @return void
     */
    public function setSchema(?SchemaInterface $schema): void
    {
        $this->schema = $schema;
    }
}
