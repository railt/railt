<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base;

use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Compiler\Reflection\Base\Definitions\BaseDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\SchemaDefinition;
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
     * @var SchemaDefinition|null
     */
    private $schema;

    /**
     * @var array|TypeDefinition[]
     */
    protected $types = [];

    /**
     * @var array|Definition[]
     */
    protected $definitions = [];

    /**
     * @var ReadableInterface
     */
    protected $file;

    /**
     * @return null|SchemaDefinition
     */
    public function getSchema(): ?SchemaDefinition
    {
        if ($this->schema === null) {
            foreach ($this->types as $type) {
                if ($type instanceof SchemaDefinition) {
                    return $this->schema = $type;
                }
            }
        }

        return $this->schema;
    }

    /**
     * @return iterable
     */
    public function getTypeDefinitions(): iterable
    {
        return \array_values($this->types);
    }

    /**
     * @param string $name
     * @return null|TypeDefinition
     */
    public function getTypeDefinition(string $name): ?TypeDefinition
    {
        return $this->types[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasTypeDefinition(string $name): bool
    {
        return \array_key_exists($name, $this->types);
    }

    /**
     * @return int
     */
    public function getNumberOfTypeDefinitions(): int
    {
        return \count($this->types);
    }

    /**
     * @return iterable
     */
    public function getDefinitions(): iterable
    {
        return \array_values(\array_merge($this->types, $this->definitions));
    }

    /**
     * @return ReadableInterface
     */
    public function getFile(): ReadableInterface
    {
        return $this->file;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // File
            'file',

            // instanceof TypeDefinition
            'types',

            // instanceof Definition
            'definitions'
        ]);
    }
}
