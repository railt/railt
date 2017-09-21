<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Compiler;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Interface Dictionary
 * @package Railt\Reflection\Compiler
 */
interface Dictionary
{
    /**
     * @param TypeInterface $type
     * @param bool $force
     * @return Dictionary
     */
    public function register(TypeInterface $type, bool $force = false): Dictionary;

    /**
     * @param string $name
     * @param Document|null $document
     * @return null|TypeInterface
     */
    public function get(string $name, Document $document = null): ?TypeInterface;

    /**
     * @param Document|null $document
     * @return TypeInterface[]|NamedTypeInterface[]
     */
    public function all(Document $document = null): array;

    /**
     * @param string $name
     * @param Document|null $document
     * @return bool
     */
    public function has(string $name, Document $document = null): bool;
}
