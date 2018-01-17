<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Standard\GraphQLDocument;
use Railt\Reflection\Standard\StandardType;
use Railt\Reflection\Support;
use Railt\SDL\Exceptions\CompilerException;
use Railt\SDL\Exceptions\UnexpectedTokenException;
use Railt\SDL\Exceptions\UnrecognizedTokenException;
use Railt\SDL\Runtime\CallStack;
use Railt\SDL\Parser\Factory as ParserFactory;
use Railt\SDL\Reflection\Builder\DocumentBuilder;
use Railt\SDL\Reflection\Builder\Process\Compilable;
use Railt\SDL\Reflection\Coercion\Factory;
use Railt\SDL\Reflection\Coercion\TypeCoercion;
use Railt\SDL\Reflection\CompilerInterface;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Reflection\Loader;
use Railt\SDL\Reflection\Validation\Base\ValidatorInterface;
use Railt\SDL\Reflection\Validation\Definitions;
use Railt\SDL\Reflection\Validation\Validator;
use Railt\Storage\ArrayPersister;
use Railt\Storage\Persister;
use Railt\Storage\Proxy;

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
     * @var ParserFactory
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
        $this->parser    = new ParserFactory();
        $this->loader    = new Loader($this, $this->stack);
        $this->validator = new Validator($this->stack);
        $this->coercion  = new Factory();

        $this->persister = $this->bootPersister($persister);

        $this->add($this->getStandardLibrary());
    }

    /**
     * @param Document $document
     * @return CompilerInterface
     * @throws \Railt\SDL\Exceptions\CompilerException
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
     * @param Document|DocumentBuilder $document
     * @return Document
     * @throws \OutOfBoundsException
     */
    private function complete(Document $document): Document
    {
        $this->load($document);

        $build = function (Definition $definition): void {
            $this->stack->push($definition);

            if ($definition instanceof Compilable) {
                $definition->compile();
            }

            if ($definition instanceof TypeDefinition) {
                $this->normalize($definition);
            }

            if (! ($definition instanceof StandardType)) {
                $this->validator->group(Definitions::class)->validate($definition);
            }

            $this->stack->pop();
        };

        foreach ($document->getDefinitions() as $definition) {
            $build($definition);
        }

        if ($document instanceof DocumentBuilder) {
            foreach ($document->getInvocableTypes() as $definition) {
                $build($definition);
            }
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
     * @param \Closure $then
     * @return CompilerInterface
     */
    public function autoload(\Closure $then): CompilerInterface
    {
        $this->loader->autoload($then);

        return $this;
    }

    /**
     * @param Readable $readable
     * @return Document
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws CompilerException
     */
    public function compile(Readable $readable): Document
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
        return function (Readable $readable): Document {
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
     * @return ParserFactory
     */
    public function getParser(): ParserFactory
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
