<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Parser\Parser;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Compiler\Stdlib;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\UnrecognizedNodeException;
use Railt\Reflection\Reflection\Document;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class Compiler
 * @package Railt\Reflection
 */
class Compiler
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Autoloader
     */
    private $autoloader;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var Stdlib
     */
    private $stdlib;

    /**
     * Compiler constructor.
     * @param Parser|null $parser
     * @throws \Railt\Parser\Exceptions\ParsingException
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(Parser $parser = null)
    {
        $this->parser     = $parser ?? new Parser();
        $this->autoloader = new Autoloader($this);
        $this->dictionary = new Dictionary($this->autoloader);
        $this->stdlib     = new Stdlib($this->dictionary);
    }

    /**
     * @param ReadableInterface $file
     * @return DocumentTypeInterface
     * @throws UnrecognizedNodeException
     * @throws TypeConflictException
     * @throws \LogicException
     * @throws UnrecognizedTokenException
     */
    public function compile(ReadableInterface $file): DocumentTypeInterface
    {
        $ast = $this->parser->parse($file);

        return new Document($file->getPathname(), $ast, $this->dictionary);
    }

    /**
     * @return Parser
     */
    public function getParser(): Parser
    {
        return $this->parser;
    }

    /**
     * @return Autoloader
     */
    public function getAutoloader(): Autoloader
    {
        return $this->autoloader;
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }

    /**
     * @return Stdlib
     */
    public function getStdlib(): Stdlib
    {
        return $this->stdlib;
    }
}
