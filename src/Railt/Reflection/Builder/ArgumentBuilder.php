<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\BaseArgument;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\TypeIndicationBuilder;
use Railt\Reflection\Contracts\Behavior\Nameable;

/**
 * Class ArgumentBuilder
 */
class ArgumentBuilder extends BaseArgument implements Compilable
{
    use Builder;
    use TypeIndicationBuilder;

    /**
     * ArgumentBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @param Nameable $parent
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document, Nameable $parent)
    {
        $this->parent = $parent;
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Value') {
            $this->hasDefaultValue = true;
            $this->defaultValue = ValueCoercion::parse($ast->getChild(0));

            return true;
        }

        return false;
    }
}
