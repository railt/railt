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
use Serafim\Railgun\Reflection\Abstraction\DirectiveTypeInterface;
use Serafim\Railgun\Reflection\Common\Arguments;
use Serafim\Railgun\Reflection\Common\HasLinkingStageInterface;
use Serafim\Railgun\Reflection\Common\HasName;
use Serafim\Railgun\Reflection\Common\LinkingStage;

/**
 * Class DirectiveDefinition
 * @package Serafim\Railgun\Reflection
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
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getTargets(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function hasTarget(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getTarget(string $name): ?string
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Directive';
    }
}
