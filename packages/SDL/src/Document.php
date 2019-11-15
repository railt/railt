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
    public array $typeMap = [];

    /**
     * @var array|DirectiveInterface[]
     */
    public array $directives = [];

    /**
     * @var SchemaInterface|null
     */
    public ?SchemaInterface $schema = null;

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
}
