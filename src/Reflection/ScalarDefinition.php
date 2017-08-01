<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Reflection\Abstraction\ScalarTypeInterface;
use Serafim\Railgun\Reflection\Common\Directives;
use Serafim\Railgun\Reflection\Common\LinkingStage;
use Serafim\Railgun\Reflection\Common\HasLinkingStageInterface;
use Serafim\Railgun\Reflection\Common\HasName;

/**
 * Class ScalarDefinition
 * @package Serafim\Railgun\Reflection
 */
class ScalarDefinition extends Definition implements
    ScalarTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Directives;
    use LinkingStage;

    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }
}
