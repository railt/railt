<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Adapters\AdapterInterface;
use Railt\Compiler\Compiler;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Class Application
 */
class Application
{
    private const DEFAULT_GRAPHQL_ADAPTER = \Railt\Adapters\Webonyx\Adapter::class;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * Application constructor.
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param ReadableInterface $sdl
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\SchemaException
     */
    public function request(ReadableInterface $sdl, RequestInterface $request): ResponseInterface
    {
        return $this->buildAdapter($sdl)->request($request);
    }

    /**
     * @param ReadableInterface $sdl
     * @return AdapterInterface
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\SchemaException
     */
    private function buildAdapter(ReadableInterface $sdl): AdapterInterface
    {
        $document = $this->getDocument($sdl);
        $schema   = $this->getSchema($document);

        return $this->getAdapter($schema);
    }

    /**
     * @param ReadableInterface $sdl
     * @return Document
     * @throws \Railt\Compiler\Exceptions\SchemaException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     */
    private function getDocument(ReadableInterface $sdl): Document
    {
        return $this->compiler->compile($sdl);
    }

    /**
     * @param Document $document
     * @return SchemaDefinition
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     */
    private function getSchema(Document $document): SchemaDefinition
    {
        $schema = $document->getSchema();

        if ($schema === null) {
            $error = \sprintf('The document %s must contain a schema definition', $document->getFileName());
            throw new TypeNotFoundException($error, $this->compiler->getStack());
        }

        return $schema;
    }

    /**
     * @param SchemaDefinition $schema
     * @return AdapterInterface
     */
    protected function getAdapter(SchemaDefinition $schema): AdapterInterface
    {
        $adapter = self::DEFAULT_GRAPHQL_ADAPTER;

        return new $adapter($this->compiler->getDictionary(), $schema);
    }
}
