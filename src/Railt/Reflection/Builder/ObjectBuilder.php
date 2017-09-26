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
use Railt\Reflection\Base\BaseObject;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Builder\Support\FieldsBuilder;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends BaseObject implements Compilable
{
    use Builder;
    use FieldsBuilder;

    /**
     * SchemaBuilder constructor.
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
     */
    public function compile(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Implements':
                /** @var TreeNode $child */
                foreach ($ast->getChildren() as $child) {
                    $name = $child->getChild(0)->getValueValue();
                    $this->interfaces[$name] = $this->getCompiler()->get($name);
                }

                return true;
        }

        return false;
    }


}
