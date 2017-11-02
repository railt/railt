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
use Railt\Compiler\Reflection\Base\Definitions\BaseSchema;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends BaseSchema implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->name = static::DEFAULT_SCHEMA_NAME;
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\BuildingException
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Query':
                $this->query = $this->getValidator()
                    ->uniqueDefinition($this->query, $this->fetchType($ast));

                return true;

            case '#Mutation':
                $this->mutation = $this->getValidator()
                    ->uniqueDefinition($this->mutation, $this->fetchType($ast));

                return true;

            case '#Subscription':
                $this->subscription = $this->getValidator()
                    ->uniqueDefinition($this->subscription, $this->fetchType($ast));

                return true;
        }

        return false;
    }

    /**
     * @param TreeNode $ast
     * @return ObjectDefinition|Definition
     * @throws \Railt\Compiler\Exceptions\BuildingException
     */
    private function fetchType(TreeNode $ast): ObjectDefinition
    {
        /**
         * <code>
         * #Query|#Mutation|#Subscription   *->getChild(0)
         *     #Type                        *->getChild(0)
         *         token(T_NAME, TypeName)  *->getValueValue()
         * </code>
         */
        $name = $ast->getChild(0)->getChild(0)->getValueValue();

        return $this->getCompiler()->get($name);
    }
}
