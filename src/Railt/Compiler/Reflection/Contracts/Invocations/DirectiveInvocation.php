<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Invocations;

use Railt\Compiler\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Interface DirectiveInvocation
 */
interface DirectiveInvocation extends DependentDefinition, Invocable
{
    /**
     * @return DirectiveDefinition
     */
    public function getDefinition(): DirectiveDefinition;

    /**
     * @return iterable|ArgumentInvocation[]
     */
    public function getPassedArguments(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedArgument(string $name): bool;

    /**
     * @param string $name
     * @return null|ArgumentInvocation
     */
    public function getPassedArgument(string $name): ?ArgumentInvocation;

    /**
     * @return int
     */
    public function getNumberOfPassedArguments(): int;
}
