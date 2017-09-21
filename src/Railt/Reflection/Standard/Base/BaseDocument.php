<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Base;

use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\SchemaType;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Standard\StandardType;

/**
 * Class BaseDocument
 */
abstract class BaseDocument implements Document, StandardType
{
    /**
     * @var array|TypeInterface[]
     */
    protected $types = [];

    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * BaseDocument constructor.
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }

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
