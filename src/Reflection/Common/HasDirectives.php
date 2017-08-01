<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Reflection\Abstraction\CalleeDirectiveInterface;
use Serafim\Railgun\Reflection\Abstraction\Common\HasDirectivesInterface;

/**
 * Trait HasDirectives
 * @package Serafim\Railgun\Reflection\Common
 * @mixin HasDirectivesInterface
 */
trait HasDirectives
{
    /**
     * @var array|CalleeDirectiveInterface[]
     */
    private $directives = [];

    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    protected function compileHasDirectives(TreeNode $ast, Dictionary $dictionary): void
    {
        $allowed = in_array($ast->getId(), $this->astHasFields ?? ['#Directive'], true);

        if ($allowed) {
            throw new CompilerException('TODO: Add directives compilation for ' . get_class($this));
        }
    }

    /**
     * @return iterable|CalleeDirectiveInterface[]
     */
    public function getDirectives(): iterable
    {
        return array_values($this->directives);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return array_key_exists($name, $this->directives);
    }

    /**
     * @param string $name
     * @return null|CalleeDirectiveInterface
     */
    public function getDirective(string $name): ?CalleeDirectiveInterface
    {
        return $this->directives[$name] ?? null;
    }
}
