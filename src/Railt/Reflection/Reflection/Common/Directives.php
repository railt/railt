<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Abstraction\CalleeDirectiveInterface;
use Railt\Reflection\Abstraction\Common\HasDirectivesInterface;
use Railt\Reflection\Exceptions\NotImplementedException;
use Railt\Reflection\Reflection\Document;

/**
 * Trait Directives
 * @package Railt\Reflection\Reflection\Common
 * @mixin HasDirectivesInterface
 */
trait Directives
{
    /**
     * @var array|CalleeDirectiveInterface[]
     */
    private $directives = [];

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

    /**
     * TODO Implement it in future
     *
     * @param Document $document
     * @param TreeNode $ast
     */
    protected function compileDirectives(Document $document, TreeNode $ast): void
    {
        $allowed = in_array($ast->getId(), (array)($this->astHasFields ?? ['#Directive']), true);

        if ($allowed) {
            throw new NotImplementedException();
        }
    }
}
