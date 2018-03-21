<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Builder\Definitions;

use Railt\Compiler\Parser\Ast\LeafInterface;
use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Compiler\Parser\Ast\RuleInterface;
use Railt\Reflection\Base\Definitions\BaseSchema;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\SDL\Exceptions\CompilerException;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Builder\Process\Compiler;

/**
 * Class SchemaBuilder
 */
class SchemaBuilder extends BaseSchema implements Compilable
{
    use Compiler;
    use DirectivesBuilder;

    /**
     * SchemaBuilder constructor.
     *
     * @param NodeInterface $ast
     * @param DocumentBuilder $document
     */
    public function __construct(NodeInterface $ast, DocumentBuilder $document)
    {
        $this->boot($ast, $document);
        $this->name   = ($this->name ?? static::DEFAULT_SCHEMA_NAME);
        $this->offset = $this->resolveSchemaOffset($ast);
    }

    /**
     * @param NodeInterface|RuleInterface $rules
     * @return int
     */
    private function resolveSchemaOffset(NodeInterface $rules): int
    {
        foreach ($rules->getChildren() as $node) {
            if ($node instanceof LeafInterface && $node->is('T_SCHEMA')) {
                return $node->getOffset();
            }
        }

        return 0;
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
