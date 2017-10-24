<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts;

use Railt\Compiler\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;

/**
 * The Document is an object that contains information
 * about all types available in one same context.
 *
 * This can be, for example, a GraphQL schema file.
 */
interface Document extends Definition
{
    /**
     * Should return the name of file if the Document is physically located on
     * the file system. Otherwise the Document should return a unique name
     * within the context of the runtime environment,
     * for example: "GraphQL Standard Library".
     *
     * @return string The name of file or unique document name.
     */
    public function getName(): string;

    /**
     * A Document can contain a root api element, which is represented as a
     * Schema object. In the event that the Document is not the main document
     * (ie, part of another document), then the Schema declaration will be
     * missing and this method should return `null` value.
     *
     * @return null|SchemaDefinition Schema object definition or null.
     */
    public function getSchema(): ?SchemaDefinition;

    /**
     * @param string|null $typeOf
     * @return iterable|Definition[]
     */
    public function getTypes(string $typeOf = null): iterable;

    /**
     * @param string $name
     * @return null|Definition
     */
    public function getDefinition(string $name): ?Definition;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDefinition(string $name): bool;

    /**
     * @return int
     */
    public function getNumberOfDefinitions(): int;
}
