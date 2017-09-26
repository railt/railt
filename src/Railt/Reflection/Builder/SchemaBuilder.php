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
use Railt\Reflection\Base\BaseSchema;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends BaseSchema implements Compilable
{
    use Builder;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    public function compile(TreeNode $ast): bool
    {
        $type = null;

        switch ($ast->getId()) {
            case '#Query':
                $this->query = $type = $this->fetchType($ast);
                break;

            case '#Mutation':
                $this->mutation = $type = $this->fetchType($ast);
                break;

            case '#Subscription':
                $this->subscription = $type = $this->fetchType($ast);
                break;
        }

        if ($type === null) {
            $this->throwInvalidAstNodeError($ast);
        }

        return true;
    }

    /**
     * @param TreeNode $ast
     * @return ObjectType|TypeInterface
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    private function fetchType(TreeNode $ast): ObjectType
    {
        $field = $ast->getChild(0);

        if ($field->getId() !== '#Type') {
            $this->throwInvalidAstNodeError($field);
        }

        $name = $field->getChild(0)->getValueValue();

        if (! \is_string($name)) {
            $this->throwInvalidAstNodeError($field);
        }

        return $this->getCompiler()->get($name);
    }
}
