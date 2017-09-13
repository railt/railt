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
use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\ExtendTypeInterface;
use Railt\Reflection\Exceptions\NotImplementedException;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\Fields;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class ExtendDefinition
 * @package Railt\Reflection\Reflection
 */
class ExtendDefinition extends Definition implements
    ExtendTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Fields;
    use Directives;
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
     * @return DefinitionInterface
     */
    public function getTarget(): DefinitionInterface
    {
        throw new NotImplementedException();
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Extender';
    }
}
