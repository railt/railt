<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Error\SyntaxError;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use GraphQL\Type\Schema;
use Railt\Http\Identifiable;
use Railt\Http\RequestInterface;

/**
 * Class Executor
 */
class Executor
{
    /**
     * @var \Closure
     */
    private $executor;

    /**
     * Executor constructor.
     *
     * @param Identifiable $connection
     * @param Schema $schema
     */
    public function __construct(Identifiable $connection, Schema $schema)
    {
        $this->executor = $this->getExecutor($connection, $schema);
    }

    /**
     * @param Identifiable $connection
     * @param Schema $schema
     * @return \Closure
     */
    private function getExecutor(Identifiable $connection, Schema $schema): \Closure
    {
        return function (RequestInterface $request) use ($connection, $schema) {
            $vars = $request->getVariables();
            $query = $this->parse($request->getQuery());

            $this->analyzeRequest($request, $query, $operation = $request->getOperation());

            $context = new Context($connection, $request);

            return GraphQL::executeQuery($schema, $query, null, $context, $vars, $operation);
        };
    }

    /**
     * @param RequestInterface $request
     * @param DocumentNode $ast
     * @param string|null $operation
     */
    private function analyzeRequest(RequestInterface $request, DocumentNode $ast, string $operation = null): void
    {
        /** @var OperationDefinitionNode $node */
        foreach ($ast->definitions as $node) {
            if ($node->kind === 'OperationDefinition') {
                $realOperationName = $this->readQueryName($node);

                if ($operation === $realOperationName) {
                    $request->withOperation($realOperationName);
                    $request->withQueryType($node->operation);

                    return;
                }
            }
        }
    }

    /**
     * @param OperationDefinitionNode $operation
     * @return string|null
     */
    private function readQueryName(OperationDefinitionNode $operation): ?string
    {
        if ($operation->name === null) {
            return null;
        }

        return (string)$operation->name->value;
    }

    /**
     * @param string $query
     * @return DocumentNode
     * @throws SyntaxError
     */
    private function parse(string $query): DocumentNode
    {
        return Parser::parse(new Source($query ?: '', 'GraphQL'));
    }

    /**
     * @param RequestInterface $request
     * @return ExecutionResult
     */
    public function execute(RequestInterface $request): ExecutionResult
    {
        return ($this->executor)($request);
    }
}
