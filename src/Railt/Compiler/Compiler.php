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
use Railt\Compiler\Exceptions\SchemaException;
use Railt\Compiler\Exceptions\UnexpectedTokenException;
use Railt\Compiler\Exceptions\UnrecognizedTokenException;
use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Persisting\ArrayPersister;
use Railt\Compiler\Persisting\Persister;
use Railt\Compiler\Persisting\Proxy;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Reflection\Dictionary;
use Railt\Compiler\Reflection\Loader;
use Railt\Compiler\Reflection\Standard\GraphQLDocument;
use Railt\Compiler\Reflection\Standard\StandardType;
use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;
use Railt\Compiler\Reflection\Validation\Definitions;
use Railt\Compiler\Reflection\Validation\Validator;

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
     * Compiler constructor.
     * @param Persister|null $persister
     * @throws CompilerException
     */
    public function __construct(Persister $persister = null)
    {
        $this->stack  = new CallStack();
        $this->parser = new Parser();

        $this->loader = new Loader($this, $this->stack);

        $this->validator = new Validator($this->stack);

        $this->persister = $this->bootPersister($persister);

        try {
            $this->bootStandardLibrary();
        } catch (\OutOfBoundsException $fatal) {
            throw CompilerException::wrap($fatal);
        }
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
    private function bootStandardLibrary(array $extensions = []): GraphQLDocument
    {
        return $this->complete(new GraphQLDocument($this, $extensions));
    }

    /**
     * @param Document $document
     * @return Document
     */
    private function complete(Document $document): Document
    {
        // Register
        $this->completeRegistration($document);

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
     * @return void
     */
    private function completeRegistration(Document $document): void
    {
        foreach ($document->getTypeDefinitions() as $type) {
            $this->stack->push($type);
            $this->register($type);
            $this->stack->pop();
        }
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
     * @throws \Throwable|\Error|CompilerException|SchemaException
     */
    public function compile(ReadableInterface $readable): Document
    {
        try {
            /** @var DocumentBuilder $document */
            $document = $this->persister->remember($readable, $this->onCompile());

            return $document->withCompiler($this);
        } catch (\Throwable | \Error $error) {
            throw $error;
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
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->loader;
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
     * @return iterable
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
