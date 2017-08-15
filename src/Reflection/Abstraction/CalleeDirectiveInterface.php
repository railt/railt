<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Abstraction;

/**
 * Interface DirectiveInterface
 * @package Railgun\Reflection\Abstraction
 */
interface CalleeDirectiveInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return iterable|CalleeArgumentInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|CalleeArgumentInterface
     */
    public function getArgument(string $name): ?CalleeArgumentInterface;

    /**
     * TODO Add directive definition resolving
     * @return NamedDefinitionInterface
     */
    // public function getDefinition(): NamedDefinitionInterface;

    /**
     * TODO Add parent relation type
     * @return DefinitionInterface
     */
    // public function getParentType(): DefinitionInterface;
}
