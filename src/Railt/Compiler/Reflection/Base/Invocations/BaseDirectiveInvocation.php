<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Invocations;

use Railt\Compiler\Reflection\Base\Dependent\BaseDependent;
use Railt\Compiler\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Invocations\ArgumentInvocation;
use Railt\Compiler\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * Class BaseDirectiveInvocation
 */
abstract class BaseDirectiveInvocation extends BaseDependent implements DirectiveInvocation
{
    /**
     * Directive type name
     */
    protected const TYPE_NAME = 'Directive';

    /**
     * @var DirectiveDefinition
     */
    protected $directive;

    /**
     * @var array|ArgumentInvocation[]
     */
    protected $arguments = [];

    /**
     * @return Definition
     */
    public function getParent(): Definition
    {
        return $this->resolve()->parent;
    }

    /**
     * @return DirectiveDefinition
     */
    public function getDefinition(): DirectiveDefinition
    {
        return $this->resolve()->directive;
    }

    /**
     * @return iterable
     */
    public function getPassedArguments(): iterable
    {
        return \array_values($this->resolve()->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPassedArgument(string $name): bool
    {
        return \array_key_exists($name, $this->resolve()->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentInvocation
     */
    public function getPassedArgument(string $name): ?ArgumentInvocation
    {
        return $this->resolve()->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfPassedArguments(): int
    {
        return \count($this->resolve()->arguments);
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // Definition
            'directive',

            // Arguments
            'arguments'
        ]);
    }
}
