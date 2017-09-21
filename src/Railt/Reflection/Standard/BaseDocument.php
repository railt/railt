<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\SchemaType;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Class BaseDocument
 */
abstract class BaseDocument implements Document
{
    /**
     *
     */
    private const CONSTANT_IDENTIFIER = '00000000-0000-0000-0000-000000000000';

    /**
     * @var array|TypeInterface[]
     */
    protected $types = [];

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getDeprecationReason(): string
    {
        return '';
    }

    /**
     * @return null|SchemaType
     */
    public function getSchema(): ?SchemaType
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return self::CONSTANT_IDENTIFIER;
    }

    /**
     * @return iterable
     */
    public function getTypes(): iterable
    {
        return \array_values($this->types);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return \array_key_exists($name, $this->types);
    }

    /**
     * @param string $name
     * @return null|TypeInterface
     */
    public function getType(string $name): ?TypeInterface
    {
        return $this->types[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfTypes(): int
    {
        return \count($this->types);
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'GraphQL';
    }
}
