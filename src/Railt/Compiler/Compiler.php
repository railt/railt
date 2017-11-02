<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Psr\Log\LoggerInterface;
use Railt\Compiler\Exceptions\CompilerException;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Exceptions\UnexpectedTokenException;
use Railt\Compiler\Exceptions\UnrecognizedTokenException;
use Railt\Compiler\Filesystem\ReadableInterface;
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
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Class Compiler
 */
class Compiler implements CompilerInterface
{
    private const LOG_BEGIN = '  ╭● ';
    private const LOG_SUB_BEGIN = '╰▷╭─○ ';
    private const LOG_POINT = '  ├┄ ';
    private const LOG_SUB_END = '╭╶┴─▷ ';
    private const LOG_END = '  ╰──▶ ';

    use Support;

    /**
     * @var int
     */
    private $depth = 0;

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
     * @var Validator
     */
    private $validator;

    /**
     * Compiler constructor.
     * @param Persister|null $persister
     * @param LoggerInterface|null $logger
     * @throws \Railt\Compiler\Exceptions\InitializationException
     */
    public function __construct(Persister $persister = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->validator = new Validator();

        $this->parser = new Parser($logger);
        $this->loader = new Loader($this);

        $this->persister = $this->bootPersister($persister);

        $this->logCompilerBootstrap();

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
     * @param Persister|null $persister
     * @return void
     */
    private function logCompilerBootstrap(Persister $persister = null): void
    {
        $storage = $persister
            ? \class_basename($persister)
            : 'default ' . \class_basename($this->persister);

        $this->log('Create Compiler with %s storage', $storage);
    }

    /**
     * @param string|\Throwable $message
     * @param string[] ...$params
     * @return void
     */
    public function log($message, string ...$params): void
    {
        if ($this->logger !== null) {
            $depth = \str_repeat('  ', \max($this->depth - 1, 0));

            if ($message instanceof \Throwable) {
                [$error, $msg] = [\class_basename($message), $message->getMessage()];
                $prefix = $this->depth > 1 ? self::LOG_SUB_END : self::LOG_END;
                $message = \sprintf($prefix . '[✗] %s: %s', $error, $msg);

                $this->logger->error($depth . \sprintf($message, ...$params));

                return;
            }

            $this->logger->debug($depth . \sprintf($message, ...$params));
        }
    }

    /**
     * @param array $extensions
     * @return GraphQLDocument|Document
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
            // Compile
            $this->completeCompilation($document, $definition);

            // Validate
            $this->completeValidation($document, $definition);
        }

        if (! ($document instanceof StandardType)) {
            $prefix = $this->depth > 1 ? self::LOG_SUB_END : self::LOG_END;
            $this->log($prefix . '[✓] Complete compilation of %s', $document->getName());
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
            if (! ($document instanceof StandardType)) {
                $this->log(self::LOG_POINT . 'Load %s', $this->typeToString($type));
            }

            $this->register($type);
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
     * @param Document $document
     * @param Definition $definition
     * @return void
     */
    private function completeCompilation(Document $document, Definition $definition): void
    {
        if ($definition instanceof Compilable) {
            if (! ($document instanceof StandardType)) {
                $this->log(self::LOG_POINT . 'Building the %s', $this->typeToString($definition));
            }

            $definition->compile();
        }
    }

    /**
     * @param Document $document
     * @param Definition $definition
     * @return void
     */
    private function completeValidation(Document $document, Definition $definition): void
    {
        if (! ($document instanceof StandardType)) {
            $this->log(self::LOG_POINT . 'Verification of the correctness of the construction of %s',
                $this->typeToString($definition));
            $this->validator->verifyDefinition($definition);
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
     * @throws \Throwable
     */
    public function compile(ReadableInterface $readable): Document
    {
        ++$this->depth;

        try {
            /** @var DocumentBuilder $document */
            $document = $this->persister->remember($readable, $this->onCompile());

            return $document->withCompiler($this);
        } catch (\Throwable $error) {
            $this->log($error);
            throw $error;
        } finally {
            --$this->depth;
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
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
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
}
