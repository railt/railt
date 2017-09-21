<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\ArgumentBuilder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Containers\HasArguments;
use Railt\Reflection\Contracts\Types\ArgumentType;

/**
 * Trait Arguments
 * @mixin HasArguments
 */
trait Arguments
{
    private $arguments = [];

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function compileArguments(TreeNode $ast): bool
    {
        /** @var Nameable $this */
        if ($ast->getId() === '#Argument') {
            $argument = new ArgumentBuilder($ast, $this->getDocument(), $this);
            $this->arguments[$argument->getName()] = $argument;
            return true;
        }

        return false;
    }

    /**
     * @return iterable|ArgumentType[]
     */
    public function getArguments(): iterable
    {
        return \array_values($this->compiled()->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentType
     */
    public function getArgument(string $name): ?ArgumentType
    {
        return $this->compiled()->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments(): int
    {
        return \count($this->compiled()->arguments);
    }

    /**
     * @return int
     */
    public function getNumberOfRequiredArguments(): int
    {
        $filtered = \array_filter($this->compiled()->arguments, function(ArgumentType $type): bool {
            return !$type->hasDefaultValue();
        });

        return \count($filtered);
    }

    /**
     * @return int
     */
    public function getNumberOfOptionalArguments(): int
    {
        $filtered = \array_filter($this->compiled()->arguments, function(ArgumentType $type): bool {
            return $type->hasDefaultValue();
        });

        return \count($filtered);
    }
}
