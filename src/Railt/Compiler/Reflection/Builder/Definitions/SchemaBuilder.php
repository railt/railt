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
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
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
     * @param string $field
     * @param Definition $definition
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function validateFieldType(string $field, Definition $definition): void
    {
        if ($definition instanceof ObjectDefinition) {
            return;
        }

        $error = \sprintf('The %s must be instance of Object, but %s given.', $field, $definition);
        throw new TypeConflictException($error, $this->getCompiler()->getStack());
    }

    /**
     * @param TreeNode $ast
     * @return ObjectDefinition|Definition
     */
    private function fetchType(TreeNode $ast): Definition
    {
        /**
         * <code>
         * #Query|#Mutation|#Subscription   *->getChild(0)
         *     #Type                        *->getChild(0)
         *         token(T_NAME, TypeName)  *->getValueValue()
         * </code>
         */
        $name = $ast->getChild(0)->getChild(0)->getValueValue();

        return $this->load($name);
    }
}
