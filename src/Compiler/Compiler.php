<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Hoa\File\Read;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Exception\UnrecognizedToken;
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;

/**
 * Class Compiler
 * @package Serafim\Railgun\Compiler
 */
class Compiler
{
    /**
     * Default grammar path
     */
    private const GRAMMAR_PP = __DIR__ . '/../../resources/processing/grammar.pp';

    /**
     * @var Parser
     */
    private $llk;

    /**
     * Compiler constructor.
     * @param string|null $grammar
     * @throws \Serafim\Railgun\Compiler\Exceptions\CompilerException
     */
    public function __construct(string $grammar = null)
    {
        $this->llk = $this->parser($grammar ?? self::GRAMMAR_PP);
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
        } catch (\Hoa\Compiler\Exception $e) {
            throw new CompilerException($e->getMessage());
        }
    }

    /**
     * @param string $filePath
     * @return Definition
     * @throws \Serafim\Railgun\Compiler\Exceptions\NotReadableException
     * @throws UnexpectedTokenException
     */
    public function parseFile(string $filePath): Definition
    {
        $file = $this->createFileSystemInfo($filePath);
        $sources = $this->read($file);

        return $this->parse($sources, $file);
    }

    /**
     * @param string $sources
     * @param null|\SplFileInfo $file
     * @return Definition
     * @throws UnexpectedTokenException
     */
    public function parse(string $sources, ?\SplFileInfo $file = null): Definition
    {
        try {
            $ast = $this->llk->parse($sources);
        } catch (UnexpectedToken $e) {
            throw new UnexpectedTokenException($e, $file);
        } catch (UnrecognizedToken $e) {
            throw new UnexpectedTokenException($e, $file);
        }

        return new Definition($sources, $ast, $file);
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
}
