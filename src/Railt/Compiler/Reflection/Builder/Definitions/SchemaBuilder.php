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
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Reflection\Base\Definitions\BaseSchema;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends BaseSchema implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * SchemaBuilder constructor.
     * TODO Offset doesn't works correctly =\
     *
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->name = static::DEFAULT_SCHEMA_NAME;
        $this->boot($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws CompilerException
     */
    protected function onCompile(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Query':
                $this->query = $this->unique($this->query, $this->fetchType($ast));

                return true;

            case '#Mutation':
                $this->mutation = $this->unique($this->mutation, $this->fetchType($ast));

                return true;

            case '#Subscription':
                $this->subscription = $this->unique($this->subscription, $this->fetchType($ast));

                return true;
        }

        return false;
    }

    /**
     * @param TreeNode $ast
     * @return ObjectDefinition|Definition
     * @throws CompilerException
     */
    private function fetchType(TreeNode $ast): Definition
    {
        $name = null;

        /**
         * <code>
         * #Query|#Mutation|#Subscription   *->getChild(0)
         *     #Type                        *->getChild(0)
         *         token(T_NAME, TypeName)  *->getValueValue()
         * </code>
         */
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getId() === '#Type') {
                return $this->load($child->getChild(0)->getValueValue());
            }
        }

        throw new CompilerException('Could not load the schema type. Probably AST is broken.');
    }
}
