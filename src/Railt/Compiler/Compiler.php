<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Hoa\Compiler\Llk\TreeNode;
use Psr\Log\LoggerInterface;
use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Exceptions\UnexpectedTokenException;
use Railt\Compiler\Exceptions\UnrecognizedTokenException;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Reflection\Dictionary;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Reflection\Loader;
use Railt\Compiler\Persisting\ArrayPersister;
use Railt\Compiler\Persisting\Persister;
use Railt\Compiler\Persisting\Proxy;
use Railt\Compiler\Reflection\Standard\GraphQLDocument;
use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Reflection\Validation\Verifiable;

/**
 * Class Compiler
 */
class Compiler implements CompilerInterface
{
    use Support;

    /**
     * @var Dictionary
     */
    private $loader;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Persister|ArrayPersister
     */
    private $persister;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Compiler constructor.
     * @param Persister|null $persister
     * @param LoggerInterface|null $logger
     * @throws \Railt\Compiler\Exceptions\InitializationException
     */
    public function __construct(Persister $persister = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        $this->parser = new Parser($logger);
        $this->loader = new Loader($this);

        $this->persister = $this->bootPersister($persister);

        $this->bootStandardLibrary();
    }

    /**
     * @param null|Persister $persister
     * @return Persister
     */
    private function bootPersister(?Persister $persister): Persister
    {
        if ($persister === null) {
            return new ArrayPersister();
        }

        if ($persister instanceof Proxy || $persister instanceof ArrayPersister) {
            return $persister;
        }

        return new Proxy(new ArrayPersister(), $persister);
    }

    /**
     * @param array $extensions
     * @return GraphQLDocument
     */
    private function bootStandardLibrary(array $extensions = []): GraphQLDocument
    {
        $std = new GraphQLDocument($this, $extensions);

        foreach ($std->getTypes() as $type) {
            $this->loader->register($type);
        }

        return $std;
    }

    /**
     * @param \Closure $then
     * @return CompilerInterface
     */
    public function registerAutoloader(\Closure $then): CompilerInterface
    {
        $this->loader->registerAutoloader($then);

        return $this;
    }

    /**
     * @param ReadableInterface $readable
     * @return Document
     * @throws TypeNotFoundException
     * @throws TypeConflictException
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     * @throws CompilerException
     */
    public function compile(ReadableInterface $readable): Document
    {
        /** @var DocumentBuilder $document */
        $document = $this->persister->remember($readable, $this->onCompile());

        return $document->withCompiler($this);
    }

    /**
     * @return \Closure
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     * @throws CompilerException
     */
    private function onCompile(): \Closure
    {
        return function (ReadableInterface $readable): Document {
            $ast = $this->parser->parse($readable);

            $document = (new DocumentBuilder($ast, $readable))->withCompiler($this);

            $this->bootTypes($document);

            return $document;
        };
    }

    /**
     * Process types
     *
     * @param Document $document
     * @return void
     */
    private function bootTypes(Document $document): void
    {
        foreach ($document->getTypes() as $type) {
            if ($type instanceof Verifiable) {
                $type->verify();
            }

            if ($type instanceof Compilable && ! $this->isUniqueType($type)) {
                $type->compileIfNotCompiled();
            }
        }
    }

    /**
     * @param Definition $type
     * @param bool $force
     * @return Dictionary
     */
    public function register(Definition $type, bool $force = false): Dictionary
    {
        return $this->loader->register($type, $force);
    }

    /**
     * @param string $name
     * @param Document|null $document
     * @return null|Definition
     */
    public function get(string $name, Document $document = null): Definition
    {
        return $this->loader->get($name, $document);
    }

    /**
     * @param Document|null $document
     * @return array
     */
    public function all(Document $document = null): array
    {
        return $this->loader->all($document);
    }

    /**
     * @param string $name
     * @param Document|null $document
     * @return bool
     */
    public function has(string $name, Document $document = null): bool
    {
        return $this->loader->has($name, $document);
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        return $this->parser->dump($ast);
    }
}
