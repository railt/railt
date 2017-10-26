<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base;

use Railt\Compiler\Reflection\Base\Definitions\BaseDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;

/**
 * Class BaseDocument
 */
abstract class BaseDocument extends BaseDefinition implements Document
{
    /**
     * Document type name
     */
    protected const TYPE_NAME = 'Document';

    /**
     * @var SchemaDefinition
     */
    protected $schema;

    /**
     * @var array|Definition[]
     */
    protected $types = [];

    /**
     * @return null|SchemaDefinition
     */
    public function getSchema(): ?SchemaDefinition
    {
        return $this->schema;
    }

    /**
     * @param string|null $typeOf
     * @return iterable
     */
    public function getTypes(string $typeOf = null): iterable
    {
        $types = \array_values($this->types);

        $filter = function (Definition $definition) use ($typeOf) {
            return $definition->getTypeName() === $typeOf || $definition instanceof $typeOf;
        };

        return $typeOf === null ? $types : \array_filter($types, $filter);
    }

    /**
     * @param string $name
     * @return null|Definition
     */
    public function getDefinition(string $name): ?Definition
    {
        return $this->types[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDefinition(string $name): bool
    {
        return \array_key_exists($name, $this->types);
    }

    /**
     * @return int
     */
    public function getNumberOfDefinitions(): int
    {
        return \count($this->types);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'types',
            'schema',
        ]);
    }
}
