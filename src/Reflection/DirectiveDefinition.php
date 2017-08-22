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
use Railt\Reflection\Abstraction\DirectiveTypeInterface;
use Railt\Reflection\Common\Arguments;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Common\LinkingStage;

/**
 * Class DirectiveDefinition
 * @package Railt\Reflection
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
