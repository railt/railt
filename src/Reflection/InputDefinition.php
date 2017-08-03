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
use Serafim\Railgun\Reflection\Abstraction\InputTypeInterface;
use Serafim\Railgun\Reflection\Common\Arguments;
use Serafim\Railgun\Reflection\Common\Directives;
use Serafim\Railgun\Reflection\Common\HasLinkingStageInterface;
use Serafim\Railgun\Reflection\Common\HasName;
use Serafim\Railgun\Reflection\Common\LinkingStage;

/**
 * Class InputDefinition
 * @package Serafim\Railgun\Reflection
 */
class InputDefinition extends Definition implements
    InputTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Arguments;
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
        return 'Input';
    }
}
