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
use Railt\Reflection\Reflection\Document;

/**
 * Interface HasLinkingStageInterface
 * @package Railt\Reflection\Reflection\Common
 *
 * @property-read TreeNode $ast
 * @property-read Document $document
 */
interface HasLinkingStageInterface
{
    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode;

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return void
     */
    public function complete(Document $document, TreeNode $ast): void;

    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool;
}
