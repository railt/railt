<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Parser\Exceptions\CompilerException;
use Railt\Parser\Exceptions\UnexpectedTokenException;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Parser\Parser;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Builder\Process\Compilable;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Compiler\Dictionary;
use Railt\Reflection\Compiler\Loader;
use Railt\Reflection\Compiler\Persisting\ArrayPersister;
use Railt\Reflection\Compiler\Persisting\Persister;
use Railt\Reflection\Compiler\Persisting\Proxy;
use Railt\Reflection\Compiler\Support;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Processable\ExtendDefinition;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\TypeNotFoundException;
use Railt\Reflection\Standard\GraphQLDocument;
use Railt\Support\Filesystem\ReadableInterface;

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
     * Compiler constructor.
     * @param Persister|null $persister
     * @throws \Railt\Parser\Exceptions\InitializationException
     */
    public function __construct(Persister $persister = null)
    {
        $this->parser    = new Parser();
        $this->loader    = new Loader($this);
        $this->persister = $this->bootPersister($persister);

        $this->bootStandardLibrary();
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
     * Process eager loading for compile-time types, like "extend"
     *
     * @param Document $document
     * @return void
     */
    private function bootProcessableTypes(Document $document): void
    {
        foreach ($document->getTypes() as $type) {
            if (!$this->isUniqueType($type) && $type instanceof Compilable) {
                $type->compileIfNotCompiled();
            }
        }
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

            $this->bootProcessableTypes($document);

            return $document;
        };
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
