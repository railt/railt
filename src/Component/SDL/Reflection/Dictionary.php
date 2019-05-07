<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection;

use Railt\Component\SDL\Contracts\Definitions\Definition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Interface Dictionary
 */
interface Dictionary
{
    /**
     * @param TypeDefinition $type
     * @param bool $force
     * @return Dictionary
     */
    public function register(TypeDefinition $type, bool $force = false): self;

    /**
     * @param string $name
     * @param Definition|null $from
     * @return TypeDefinition
     */
    public function get(string $name, Definition $from = null): TypeDefinition;

    /**
     * @return iterable|TypeDefinition[]
     */
    public function all(): iterable;

    /**
     * @param string|TypeDefinition $type
     * @return iterable|TypeDefinition[]
     */
    public function only(string $type): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
