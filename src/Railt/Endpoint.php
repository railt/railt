<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt;

use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Youshido\Adapter;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Class Endpoint
 */
class Endpoint
{
    /**
     * @var Document
     */
    private $document;

    /**
     * Endpoint constructor.
     * @param CompilerInterface $compiler
     * @param ReadableInterface $schema
     */
    public function __construct(CompilerInterface $compiler, ReadableInterface $schema)
    {
        $this->document = $compiler->compile($schema);
    }

    /**
     * @param CompilerInterface $compiler
     * @param ReadableInterface $schema
     * @return Endpoint
     */
    public static function new(CompilerInterface $compiler, ReadableInterface $schema): self
    {
        return new static($compiler, $schema);
    }

    /**
     * @return AdapterInterface
     * @throws \LogicException
     */
    protected function adapter(): AdapterInterface
    {
        return new Adapter($this->resolveSchema());
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \LogicException
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        return $this->adapter()->request($request);
    }

    /**
     * @return SchemaDefinition
     * @throws \LogicException
     */
    private function resolveSchema(): SchemaDefinition
    {
        $schema = $this->document->getSchema();

        if ($schema === null) {
            $error = 'Document file %s is wrongly configured. Schema definition required.';
            throw new \LogicException(\sprintf($error, $this->document->getFileName()));
        }

        return $schema;
    }
}
