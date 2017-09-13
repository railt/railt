<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Abstraction\DirectiveTypeInterface;
use Railt\Reflection\Exceptions\NotImplementedException;
use Railt\Reflection\Reflection\Common\Arguments;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class DirectiveDefinition
 * @package Railt\Reflection\Reflection
 */
class DirectiveDefinition extends Definition implements
    DirectiveTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Arguments;
    use LinkingStage;
    use HasDescription;

    /**
     * TODO Implement it in future
     *
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        throw new NotImplementedException();
    }

    /**
     * TODO Implement it in future
     *
     * @return iterable
     */
    public function getTargets(): iterable
    {
        throw new NotImplementedException();
    }

    /**
     * TODO Implement it in future
     *
     * @param string $name
     * @return bool
     */
    public function hasTarget(string $name): bool
    {
        throw new NotImplementedException();
    }

    /**
     * TODO Implement it in future
     *
     * @param string $name
     * @return null|string
     */
    public function getTarget(string $name): ?string
    {
        throw new NotImplementedException();
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Directive';
    }
}
