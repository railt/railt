<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Hoa\Compiler\Exception\UnrecognizedToken;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\File\Read;
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Compiler\Reflection\Definition;
use Serafim\Railgun\Compiler\Reflection\DirectiveDefinition;
use Serafim\Railgun\Compiler\Reflection\EnumDefinition;
use Serafim\Railgun\Compiler\Reflection\ExtendDefinition;
use Serafim\Railgun\Compiler\Reflection\InputDefinition;
use Serafim\Railgun\Compiler\Reflection\InterfaceDefinition;
use Serafim\Railgun\Compiler\Reflection\ObjectDefinition;
use Serafim\Railgun\Compiler\Reflection\ScalarDefinition;
use Serafim\Railgun\Compiler\Reflection\SchemaDefinition;
use Serafim\Railgun\Compiler\Reflection\UnionDefinition;

/**
 * Class Compiler
 * @package Serafim\Railgun\Compiler
 */
class Compiler
{
    /**
     * Default grammar path
     */
    private const GRAMMAR_PP = __DIR__ . '/../../resources/grammar.pp';

    /**
     * @var Parser
     */
    private $llk;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var array
     */
    private $rootNodes = [];

    /**
     * @var Autoloader
     */
    private $loader;

    /**
     * Compiler constructor.
     * @param string|null $grammar
     * @throws CompilerException
     */
    public function __construct(string $grammar = null)
    {
        $this->llk = $this->parser($grammar ?? self::GRAMMAR_PP);
        $this->loader = new Autoloader($this);
        $this->dictionary = new Dictionary($this->loader);

        $this->prepare();
    }

    /**
     * @return void
     */
    private function prepare(): void
    {
        $rootDefinitions = [
            SchemaDefinition::class,
            ObjectDefinition::class,
            InterfaceDefinition::class,
            UnionDefinition::class,
            ScalarDefinition::class,
            EnumDefinition::class,
            InputDefinition::class,
            ExtendDefinition::class,
            DirectiveDefinition::class,
        ];

        $this->registerRootNode(...$rootDefinitions);
    }

    /**
     * @return Autoloader
     */
    public function getLoader(): Autoloader
    {
        return $this->loader;
    }

    /**
     * @param string $grammar
     * @return Parser
     * @throws CompilerException
     * @throws \Hoa\Compiler\Exception
     */
    private function parser(string $grammar): Parser
    {
        try {
            return Llk::load(new Read($grammar));
        } catch (\Throwable $e) {
            throw new CompilerException($e->getMessage());
        }
    }

    /**
     * @param string[]|Definition[] ...$classes
     * @return $this|Compiler
     */
    public function registerRootNode(string ...$classes): Compiler
    {
        foreach ($classes as $class) {
            $this->rootNodes[$class::getAstId()] = $class;
        }

        return $this;
    }

    /**
     * @param TreeNode $node
     * @return null|string
     */
    public function getRootNode(TreeNode $node): ?string
    {
        return $this->rootNodes[$node->getId()] ?? null;
    }

    /**
     * @param string $filePath
     * @return Document
     * @throws Exceptions\SemanticException
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     */
    public function parseFile(string $filePath): Document
    {
        $file = $this->createFileSystemInfo($filePath);
        $sources = $this->read($file);

        return $this->parse($sources, $file);
    }

    /**
     * @param string $filePath
     * @return \SplFileInfo
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     */
    private function createFileSystemInfo(string $filePath): \SplFileInfo
    {
        $info = new \SplFileInfo($filePath);

        if (!$info->isReadable()) {
            $error = 'File "%s" not exists or not readable';
            throw new NotReadableException(sprintf($error, $info->getPathname()));
        }

        return $info;
    }

    /**
     * @param \SplFileInfo $file
     * @return string
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     */
    private function read(\SplFileInfo $file): string
    {
        $sources = @file_get_contents($file->getPathname());

        if (is_bool($sources)) {
            $error = 'Error while reading sources of "%s" file';
            throw new NotReadableException(sprintf($error, $file->getPathname()));
        }

        return $sources;
    }

    /**
     * @param string $sources
     * @param null|\SplFileInfo $file
     * @return Document
     * @throws \Serafim\Railgun\Compiler\Exceptions\SemanticException
     * @throws \RuntimeException
     * @throws \OutOfRangeException
     * @throws UnexpectedTokenException
     */
    public function parse(string $sources, ?\SplFileInfo $file = null): Document
    {
        try {
            $ast = $this->llk->parse($sources);
        } catch (UnexpectedToken $e) {
            throw new UnexpectedTokenException($e, $file);
        } catch (UnrecognizedToken $e) {
            throw new UnexpectedTokenException($e, $file);
        }

        $fileName = $file !== null ? $file->getRealPath() : '<undefined>';

        return new Document($ast, $fileName, $this);
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }
}
