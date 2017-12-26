<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Exceptions\UnexpectedTokenException;
use Railt\Compiler\Exceptions\UnrecognizedTokenException;
use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Persisting\ArrayPersister;
use Railt\Compiler\Persisting\Persister;
use Railt\Compiler\Persisting\Proxy;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Coercion\Factory;
use Railt\Compiler\Reflection\Coercion\TypeCoercion;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Dictionary;
use Railt\Compiler\Reflection\Loader;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;
use Railt\Compiler\Reflection\Validation\Definitions;
use Railt\Compiler\Reflection\Validation\Validator;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;
use Railt\Reflection\Standard\GraphQLDocument;
use Railt\Reflection\Standard\StandardType;
use Railt\Reflection\Support;

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
     * @var Validator
     */
    private $validator;

    /**
     * @var CallStack
     */
    private $stack;

    /**
     * @var Factory|TypeCoercion
     */
    private $coercion;

    /**
     * Compiler constructor.
     * @param Persister|null $persister
     * @throws \OutOfBoundsException
     * @throws CompilerException
     */
    public function __construct(Persister $persister = null)
    {
        $this->stack     = new CallStack();
        $this->parser    = new Parser($this->stack);
        $this->loader    = new Loader($this, $this->stack);
        $this->validator = new Validator($this->stack);
        $this->coercion  = new Factory();

        $this->persister = $this->bootPersister($persister);

        $this->add($this->getStandardLibrary());
    }

    /**
     * @param Document $document
     * @return CompilerInterface
     * @throws \Railt\Compiler\Exceptions\CompilerException
     */
    public function add(Document $document): CompilerInterface
    {
        try {
            $this->complete($document);
        } catch (\OutOfBoundsException $fatal) {
            throw CompilerException::wrap($fatal);
        }

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
     * @return GraphQLDocument|Document
     * @throws \OutOfBoundsException
     */
    private function getStandardLibrary(array $extensions = []): GraphQLDocument
    {
        return new GraphQLDocument($extensions);
    }

    /**
     * @param Document $document
     * @return Document
     * @throws \OutOfBoundsException
     */
    private function complete(Document $document): Document
    {
        $this->load($document);

        foreach ($document->getDefinitions() as $definition) {
            $this->stack->push($definition);

            // Compile
            $this->completeCompilation($definition);

            // Validate
            $this->completeValidation($definition);

            $this->stack->pop();
        }

        return $document;
    }

    /**
     * @param Document $document
     * @return Document|DocumentBuilder
     */
    private function load(Document $document): Document
    {
        foreach ($document->getTypeDefinitions() as $type) {
            $this->stack->push($type);
            $this->register($type);
            $this->stack->pop();
        }

        return $document;
    }

    /**
     * @param TypeDefinition $type
     * @param bool $force
     * @return Dictionary
     */
    public function register(TypeDefinition $type, bool $force = false): Dictionary
    {
        return $this->loader->register($type, $force);
    }

    /**
     * @param Definition $definition
     * @return void
     */
    private function completeCompilation(Definition $definition): void
    {
        if ($definition instanceof Compilable) {
            $definition->compile();
        }
    }

    /**
     * @param Definition $definition
     * @return void
     * @throws \OutOfBoundsException
     */
    private function completeValidation(Definition $definition): void
    {
        if (! ($definition instanceof StandardType)) {
            $this->validator->group(Definitions::class)->validate($definition);
        }
    }

    /**
     * @param \Closure $then
     * @return CompilerInterface
     */
    public function autoload(\Closure $then): CompilerInterface
    {
        $this->loader->autoload($then);

        return $this;
    }

    /**
     * @param ReadableInterface $readable
     * @return Document
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
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
     * @throws \OutOfBoundsException
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     * @throws CompilerException
     */
    private function onCompile(): \Closure
    {
        return function (ReadableInterface $readable): Document {
            $ast = $this->parser->parse($readable);

            return $this->complete(new DocumentBuilder($ast, $readable, $this));
        };
    }

    /**
     * @param string $group
     * @return ValidatorInterface
     * @throws \OutOfBoundsException
     */
    public function getValidator(string $group): ValidatorInterface
    {
        return $this->validator->group($group);
    }

    /**
     * @return Parser
     */
    public function getParser(): Parser
    {
        return $this->parser;
    }

    /**
     * @param TypeDefinition $type
     * @return TypeDefinition
     */
    public function normalize(TypeDefinition $type): TypeDefinition
    {
        return $this->coercion->apply($type);
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->loader;
    }

    /**
     * @param string $type
     * @return iterable|TypeDefinition[]
     */
    public function only(string $type): iterable
    {
        return $this->loader->only($type);
    }

    /**
     * @param string $name
     * @return TypeDefinition
     */
    public function get(string $name): TypeDefinition
    {
        return $this->loader->get($name);
    }

    /**
     * @return iterable|TypeDefinition[]
     */
    public function all(): iterable
    {
        yield from $this->loader->all();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->loader->has($name);
    }

    /**
     * @return CallStack
     */
    public function getStack(): CallStack
    {
        return $this->stack;
    }
}
