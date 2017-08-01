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
use Serafim\Railgun\Reflection\Common\Directives;
use Serafim\Railgun\Reflection\Common\Fields;
use Serafim\Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Serafim\Railgun\Reflection\Common\LinkingStage;
use Serafim\Railgun\Reflection\Common\HasLinkingStageInterface;
use Serafim\Railgun\Reflection\Common\HasName;

/**
 * Class ObjectDefinition
 * @package Serafim\Railgun\Reflection
 */
class ObjectDefinition extends Definition implements
    ObjectTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Fields;
    use Directives;
    use LinkingStage;

    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getInterfaces(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Object';
    }
}
