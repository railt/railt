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
use Serafim\Railgun\Compiler\Exceptions\{
    CompilerException, NotReadableException, UnexpectedTokenException
};
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Document;

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
     * @var Autoloader
     */
    private $loader;

    /**
     * @var GraphQLStandard
     */
    private $standard;

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
        $this->standard = new GraphQLStandard($this->dictionary);
    }

    /**
     * @param string $grammar
     * @return Parser
     * @throws CompilerException
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
     * @return Autoloader
     */
    public function getLoader(): Autoloader
    {
        return $this->loader;
    }

    /**
     * @param string $fileName
     * @return DocumentTypeInterface
     * @throws \Serafim\Railgun\Compiler\Exceptions\TypeException
     * @throws \Serafim\Railgun\Compiler\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Compiler\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     * @throws \RuntimeException
     * @throws \OutOfRangeException
     * @throws UnexpectedTokenException
     */
    public function compileFile(string $fileName): DocumentTypeInterface
    {
        $ast = $this->parseFile($fileName);

        return new Document($fileName, $ast, $this->dictionary);
    }

    /**
     * @param string $filePath
     * @return TreeNode
     * @throws Exceptions\SemanticException
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     */
    public function parseFile(string $filePath): TreeNode
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
     * @return TreeNode
     * @throws UnexpectedTokenException
     */
    public function parse(string $sources, ?\SplFileInfo $file = null): TreeNode
    {
        try {
            return $this->llk->parse($sources);
        } catch (UnexpectedToken $e) {
            throw new UnexpectedTokenException($e, $file);
        } catch (UnrecognizedToken $e) {
            throw new UnexpectedTokenException($e, $file);
        }
    }

    /**
     * @param string $sources
     * @param null|\SplFileInfo $file
     * @return DocumentTypeInterface
     * @throws \Serafim\Railgun\Compiler\Exceptions\TypeException
     * @throws \Serafim\Railgun\Compiler\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Compiler\Exceptions\CompilerException
     */
    public function compile(string $sources, ?\SplFileInfo $file = null): DocumentTypeInterface
    {
        $ast = $this->parse($sources, $file);

        $fileName = $file ? $file->getRealPath() : '<undefined>';

        return new Document($fileName, $ast, $this->dictionary);
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        return dump($ast);
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }
}
