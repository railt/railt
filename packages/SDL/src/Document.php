<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Ramsey\Collection\Map\TypedMap;
use Railt\Contracts\SDL\DocumentInterface;
use Ramsey\Collection\Map\TypedMapInterface;
use GraphQL\Contracts\TypeSystem\SchemaInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class Document
 */
final class Document implements DocumentInterface
{
    /**
     * @var TypedMapInterface|NamedTypeInterface[]
     */
    public TypedMapInterface $typeMap;

    /**
     * @var TypedMapInterface|DirectiveInterface[]
     */
    public TypedMapInterface $directives;

    /**
     * @var SchemaInterface|null
     */
    public ?SchemaInterface $schema = null;

    /**
     * Registry constructor.
     */
    public function __construct()
    {
        $this->typeMap = new TypedMap('string', NamedTypeInterface::class);
        $this->directives = new TypedMap('string', DirectiveInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeMap(): iterable
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
     * @return void
     */
    public function __clone()
    {
        if ($this->schema) {
            $this->schema = clone $this->schema;
        }

        $this->directives = clone $this->directives;
        $this->typeMap = clone $this->typeMap;
    }
}
