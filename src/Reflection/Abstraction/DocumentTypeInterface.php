<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

/**
 * Interface DocumentTypeInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface DocumentTypeInterface extends DefinitionInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @param string[] ...$types
     * @return iterable|DefinitionInterface[]
     */
    public function getDefinitions(string ...$types): iterable;

    /**
     * @return iterable|NamedDefinitionInterface[]
     */
    public function getNamedDefinitions(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasDefinition(string $name): bool;

    /**
     * @param string $name
     * @return null|NamedDefinitionInterface
     */
    public function getDefinition(string $name): ?NamedDefinitionInterface;

    /**
     * @return null|SchemaTypeInterface
     */
    public function getSchema(): ?SchemaTypeInterface;

    /**
     * @return bool
     */
    public function isStdlib(): bool;
}
