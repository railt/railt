<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Runtime;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Interface ExecutionInterface
 */
interface ExecutionInterface
{
    /**
     * @return DefinitionInterface
     */
    public function getContext(): DefinitionInterface;

    /**
     * @psalm-return iterable<string, mixed>
     * @return iterable|ValueInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return mixed
     */
    public function getArgument(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;
}
