<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railgun\Reflection\Abstraction\InterfaceTypeInterface;
use Railgun\Reflection\Common\Directives;
use Railgun\Reflection\Common\Fields;
use Railgun\Reflection\Common\HasLinkingStageInterface;
use Railgun\Reflection\Common\HasName;
use Railgun\Reflection\Common\LinkingStage;

/**
 * Class InterfaceDefinition
 * @package Railgun\Reflection
 */
class InterfaceDefinition extends Definition implements
    InterfaceTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Fields;
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
        return 'Interface';
    }
}
