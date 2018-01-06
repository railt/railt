<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Reflection\Builder\Definitions;

use Railt\Compiler\TreeNode;
use Railt\GraphQL\Exceptions\TypeConflictException;
use Railt\GraphQL\Reflection\Builder\DocumentBuilder;
use Railt\GraphQL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\GraphQL\Reflection\Builder\Process\Compilable;
use Railt\GraphQL\Reflection\Builder\Process\Compiler;
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
     * @throws \Railt\GraphQL\Exceptions\TypeConflictException
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
