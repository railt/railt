<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\TypeSystem\DocumentInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Parser\Ast\TypeSystem\Definition\SchemaDefinitionNode;

/**
 * Class ExecutorHandler
 */
abstract class ExecutorHandler implements HandlerInterface
{
    /**
     * @var DocumentInterface
     */
    private DocumentInterface $document;

    /**
     * WebonyxExecutor constructor.
     *
     * @param DocumentInterface $document
     */
    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @return SchemaDefinitionNode
     */
    protected function getSchema(): SchemaDefinitionNode
    {
        $schemas = $this->document->schemas();

        if (\count($schemas)) {
            return \reset($schemas);
        }

        throw new \LogicException('GraphQL schema not defined');
    }

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    protected function context(RequestInterface $request)
    {
        return $request;
    }
}
