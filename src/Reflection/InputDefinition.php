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
use Railgun\Exceptions\IndeterminateBehaviorException;
use Railgun\Reflection\Abstraction\InputTypeInterface;
use Railgun\Reflection\Common\Arguments;
use Railgun\Reflection\Common\Directives;
use Railgun\Reflection\Common\HasLinkingStageInterface;
use Railgun\Reflection\Common\HasName;
use Railgun\Reflection\Common\LinkingStage;

/**
 * Class InputDefinition
 * @package Railgun\Reflection
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
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Input';
    }
}
