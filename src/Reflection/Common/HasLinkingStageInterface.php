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
use Serafim\Railgun\Reflection\Document;

/**
 * Interface HasLinkingStageInterface
 * @package Serafim\Railgun\Reflection\Common
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
