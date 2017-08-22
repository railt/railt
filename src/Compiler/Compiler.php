<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Hoa\Compiler\Exception;
use Hoa\Compiler\Exception\UnexpectedToken;
use Hoa\Compiler\Exception\UnrecognizedToken;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\File\Read;
use Railt\Exceptions\CompilerException;
use Railt\Exceptions\UnexpectedTokenException;
use Railt\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Document;
use Railt\Support\File;

/**
 * Class Compiler
 * @package Railt\Compiler
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
     * Compiler constructor.
     * @param string|null $grammar
     * @throws CompilerException
     * @throws \Railt\Exceptions\SemanticException
     */
    public function __construct(string $grammar = null)
    {
        $this->llk = $this->getParser($grammar);

        $this->loader     = new Autoloader($this);
        $this->dictionary = new Dictionary($this->loader);

        new Stdlib($this->dictionary);
    }

    /**
     * @param null|string $grammar
     * @return Parser
     * @throws CompilerException
     */
    private function getParser(?string $grammar): Parser
    {
        try {
            return Llk::load($this->createReader($grammar));
        } catch (Exception $e) {
            throw CompilerException::new($e->getMessage())->code(0);
        }
    }

    /**
     * @param null|string $grammar
     * @return Read
     */
    private function createReader(?string $grammar): Read
    {
        return new Read($grammar ?? self::GRAMMAR_PP);
    }

    /**
     * @return Autoloader
     */
    public function getLoader(): Autoloader
    {
        return $this->loader;
    }

    /**
     * @param File $file
     * @return DocumentTypeInterface
     * @throws UnrecognizedTokenException
     */
    public function compile(File $file): DocumentTypeInterface
    {
        $ast = $this->parse($file);

        return new Document($file->getPathname(), $ast, $this->dictionary);
    }

    /**
     * @param File $file
     * @return TreeNode
     * @throws UnrecognizedTokenException
     */
    public function parse(File $file): TreeNode
    {
        try {
            return $this->llk->parse($file->getSources());
        } catch (UnexpectedToken $e) {
            throw UnexpectedTokenException::fromHoa($e, $file);
        } catch (UnrecognizedToken $e) {
            throw UnrecognizedTokenException::fromHoa($e, $file);
        }
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }
}
