<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Definitions;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\UnionType;
use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Base\Definitions\BaseUnion;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Exceptions\TypeConflictException;

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
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws TypeConflictException
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Relations') {
            /** @var TreeNode $relation */
            foreach ($ast->getChildren() as $relation) {
                $name = $relation->getChild(0)->getValueValue();
                $this->types[$name] = $this->getCompiler()->get($name);

                $this->checkType($this->types[$name]);
            }

            return true;
        }

        return false;
    }

    /**
     * TODO Move verification into external class
     *
     * @param Definition $type
     * @return void
     * @throws TypeConflictException
     */
    private function checkType(Definition $type): void
    {
        $error = 'Child of Union type can not be';

        if ($type instanceof UnionType) {
            throw new TypeConflictException($error . ' another Union');
        }

        if ($type instanceof InterfaceType) {
            throw new TypeConflictException($error . ' an Interface');
        }
    }
}
