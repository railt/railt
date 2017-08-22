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
use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\ExtendTypeInterface;
use Railt\Reflection\Common\Directives;
use Railt\Reflection\Common\Fields;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Common\LinkingStage;

/**
 * Class ExtendDefinition
 * @package Railt\Reflection
 */
class ExtendDefinition extends Definition implements
    ExtendTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Fields;
    use Directives;
    use LinkingStage;

    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function getTarget(): DefinitionInterface
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Extender';
    }
}
