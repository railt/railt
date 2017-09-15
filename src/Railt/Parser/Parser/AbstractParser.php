<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Parser;

use Hoa\Compiler\Exception;
use Hoa\Compiler\Exception\UnexpectedToken;
use Hoa\Compiler\Exception\UnrecognizedToken;
use Hoa\Compiler\Llk\Parser as LlkParser;
use Hoa\Compiler\Llk\TreeNode;
use Railt\Parser\Exceptions\InitializationException;
use Railt\Parser\Exceptions\UnexpectedTokenException;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Parser\Profiler;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class AbstractParser
 * @package Railt\Parser\Parser
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var LlkParser
     */
    private $parser;

    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * Parser constructor.
     * @throws InitializationException
     */
    public function __construct()
    {
        try {
            $this->parser   = $this->createParser();
            $this->profiler = new Profiler($this->parser);
        } catch (Exception $e) {
            throw new InitializationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return LlkParser
     */
    abstract protected function createParser(): LlkParser;

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        return $this->profiler->dump($ast);
    }

    /**
     * @param ReadableInterface $file
     * @return TreeNode
     * @throws UnrecognizedTokenException
     */
    public function parse(ReadableInterface $file): TreeNode
    {
        try {
            return $this->parser->parse($file->read());
        } catch (UnexpectedToken $e) {
            throw new UnexpectedTokenException($e->getMessage(), $e->getCode(), $e, $file);
        } catch (UnrecognizedToken $e) {
            throw new UnrecognizedTokenException($e->getMessage(), $e->getCode(), $e, $file);
        }
    }
}
