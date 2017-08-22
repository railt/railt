<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Exceptions\IndeterminateBehaviorException;
use Railt\Reflection\Abstraction\ScalarTypeInterface;
use Railt\Reflection\Common\Directives;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Common\LinkingStage;

/**
 * Class ScalarDefinition
 * @package Railt\Reflection
 */
class ScalarDefinition extends Definition implements
    ScalarTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Directives;
    use LinkingStage;

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        return $ast;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }
}
