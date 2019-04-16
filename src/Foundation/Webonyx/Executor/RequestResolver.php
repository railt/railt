<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Executor;

use GraphQL\Error\SyntaxError;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use GraphQL\Type\Schema;
use Railt\Component\Http\RequestInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Webonyx\Context;

/**
 * Class RequestResolver
 */
class RequestResolver
{
    /**
     * @param ConnectionInterface $connection
     * @param RequestInterface $request
     * @param Schema $schema
     * @return ExecutionResult
     * @throws SyntaxError
     */
    public static function resolve(ConnectionInterface $connection, RequestInterface $request, Schema $schema): ExecutionResult
    {
        $vars = $request->getVariables();

        $query = self::parse($request->getQuery());

        [$name, $type] = self::analyzeRequest($query, $operation = $request->getOperation());

        $request->withOperation($name);
        $request->withQueryType($type);

        $context = new Context($connection, $request);

        return GraphQL::executeQuery($schema, $query, null, $context, $vars, $operation);
    }

    /**
     * @param string $query
     * @return DocumentNode
     * @throws SyntaxError
     */
    private static function parse(string $query): DocumentNode
    {
        return Parser::parse(new Source($query ?: '', 'GraphQL'));
    }

    /**
     * @param DocumentNode $ast
     * @param string|null $operation
     * @return array
     */
    private static function analyzeRequest(DocumentNode $ast, string $operation = null): array
    {
        /** @var OperationDefinitionNode $node */
        foreach ($ast->definitions as $node) {
            if ($node->kind === 'OperationDefinition') {
                $realOperationName = self::readQueryName($node);

                if ($operation === $realOperationName) {
                    return [$realOperationName, $node->operation];
                }
            }
        }

        return [null, RequestInterface::TYPE_QUERY];
    }

    /**
     * @param OperationDefinitionNode $operation
     * @return string|null
     */
    private static function readQueryName(OperationDefinitionNode $operation): ?string
    {
        if ($operation->name === null) {
            return null;
        }

        return (string)$operation->name->value;
    }
}
