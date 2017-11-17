<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Definitions;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Definitions\BaseUnion;

/**
 * Class UnionBuilder
 */
class UnionBuilder extends BaseUnion implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * UnionBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->offset = $this->offsetPrefixedBy('union');
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeConflictException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Relations') {
            /** @var TreeNode $relation */
            foreach ($ast->getChildren() as $relation) {
                $name = $relation->getChild(0)->getValueValue();

                $child = $this->load($name);

                $this->types = $this->unique($this->types, $child);
            }

            return true;
        }

        return false;
    }
}
