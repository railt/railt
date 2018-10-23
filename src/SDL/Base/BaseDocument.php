<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base;

use Railt\Io\Readable;
use Railt\SDL\Base\Definitions\BaseDefinition;
use Railt\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\SDL\Contracts\Definitions\Definition;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Contracts\Type;

/**
 * Class BaseDocument
 */
abstract class BaseDocument extends BaseDefinition implements Document
{
    use BaseDirectivesContainer;

    /**
     * Document type name
     */
    protected const TYPE_NAME = Type::DOCUMENT;

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
     * @var Readable
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
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    /**
     * @return string
     */
    final public function getFileName(): string
    {
        return $this->file->getPathname();
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->file->getContents();
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
            'definitions',

            // Directives
            'directives',
        ]);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return self::TYPE_NAME;
    }
}
