<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Definitions;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\SDL\Exceptions\CompilerException;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;
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
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->name = static::DEFAULT_SCHEMA_NAME;
        $this->boot($ast, $document);
    }

    /**
     * @param NodeInterface $ast
     * @return bool
     * @throws CompilerException
     */
    protected function onCompile(NodeInterface $ast): bool
    {
        switch ($ast->getName()) {
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
     * @param NodeInterface|RuleInterface $ast
     * @return ObjectDefinition|Definition
     * @throws CompilerException
     */
    private function fetchType(NodeInterface $ast): Definition
    {
        $name = null;

        /**
         * <code>
         * #Query|#Mutation|#Subscription   *->getChild(0)
         *     #Type                        *->getChild(0)
         *         token(T_NAME, TypeName)  *->getValueValue()
         * </code>
         */
        foreach ($ast->getChildren() as $child) {
            if ($child->is('#Type')) {
                return $this->load($child->getChild(0)->getValue());
            }
        }

        throw new CompilerException('Could not load the schema type. Probably AST is broken.');
    }
}
