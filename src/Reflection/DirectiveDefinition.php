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
use Railgun\Reflection\Abstraction\DirectiveTypeInterface;
use Railgun\Reflection\Common\Arguments;
use Railgun\Reflection\Common\HasLinkingStageInterface;
use Railgun\Reflection\Common\HasName;
use Railgun\Reflection\Common\LinkingStage;

/**
 * Class DirectiveDefinition
 * @package Railgun\Reflection
 */
class DirectiveDefinition extends Definition implements
    DirectiveTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Arguments;
    use LinkingStage;

    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function getTargets(): iterable
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function hasTarget(string $name): bool
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function getTarget(string $name): ?string
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Directive';
    }
}
