<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Document;

/**
 * Interface HasLinkingStageInterface
 * @package Railt\Reflection\Common
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
     * @return bool
     */
    public function compileIfNotCompiled(): bool;
}
