<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Hoa\Compiler\Exception;
use Hoa\Compiler\Exception\UnexpectedToken;
use Hoa\Compiler\Exception\UnrecognizedToken;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser as LlkParser;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\File\Read;
use Railt\Parser\Exceptions\ParserException;
use Railt\Parser\Exceptions\UnexpectedTokenException;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Support\Filesystem\ReadableInterface;

/**
 * Class Parser
 * @package Railt\Parser
 */
class Parser
{
    /**
     * Default grammar path
     */
    private const GRAMMAR_PP = __DIR__ . '/graphql-idl.pp';

    /**
     * @var Parser
     */
    private $llk;

    /**
     * Compiler constructor.
     * @param string|null $grammar
     * @throws ParserException
     */
    public function __construct(string $grammar = null)
    {
        $this->llk = $this->getParser($grammar);
    }

    /**
     * @param null|string $grammar
     * @return LlkParser
     * @throws ParserException
     */
    private function getParser(?string $grammar): LlkParser
    {
        try {
            return Llk::load($this->createReader($grammar));
        } catch (Exception $e) {
            throw new ParserException($e->getMessage());
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
     * @param TreeNode $ast
     * @param bool $stdout
     * @return string
     */
    public static function dump(TreeNode $ast, bool $stdout = false): string
    {
        $result = (new Dumper($ast))->dump();

        if ($stdout) {
            echo $result;
        }

        return $result;
    }

    /**
     * @param ReadableInterface $file
     * @return TreeNode
     * @throws UnrecognizedTokenException
     */
    public function parse(ReadableInterface $file): TreeNode
    {
        try {
            return $this->llk->parse($file->read());
        } catch (UnexpectedToken $e) {
            throw UnexpectedTokenException::fromHoaException($e, $file);
        } catch (UnrecognizedToken $e) {
            throw UnrecognizedTokenException::fromHoaException($e, $file);
        }
    }
}
