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
use Railt\Reflection\Base\BaseUnion;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;
use Railt\Reflection\Contracts\Types\UnionType;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class UnionBuilder
 */
class UnionBuilder extends BaseUnion implements Compilable
{
    use Builder;

    /**
     * UnionBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
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
     * @param NamedTypeDefinition $type
     * @return void
     * @throws TypeConflictException
     */
    private function checkType(NamedTypeDefinition $type): void
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
