<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Support\Str;
use Railt\Parser\Parser;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Exceptions\BrokenAstException;

/**
 * Class Value
 * @package Railt\Reflection\Reflection
 */
final class Value
{
    /**
     * @var DocumentInterface
     */
    private $document;

    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * Value constructor.
     * @param DocumentInterface $document
     * @param TreeNode $ast
     */
    public function __construct(DocumentInterface $document, TreeNode $ast)
    {
        $this->document = $document;
        $this->ast      = $ast;
    }

    /**
     * @param DocumentInterface $document
     * @param TreeNode $ast
     * @return array|mixed|object
     * @throws BrokenAstException
     */
    public static function new(DocumentInterface $document, TreeNode $ast)
    {
        return (new Value($document, $ast))->compile();
    }

    /**
     * @return array|mixed|object
     * @throws BrokenAstException
     */
    public function compile()
    {
        return $this->parse($this->ast);
    }

    /**
     * @param TreeNode $ast
     * @return array|mixed|object
     * @throws BrokenAstException
     */
    private function parse(TreeNode $ast)
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            switch ($child->getId()) {
                case 'token':
                    return $this->parsePrimitive($child);
                case '#Object':
                    return $this->parseObject($child);
                case '#List':
                    return $this->parseArray($child);
            }
        }

        throw BrokenAstException::new('Error while parsing %s', Parser::dump($child));
    }

    /**
     * @param TreeNode $ast
     * @return mixed
     */
    private function parsePrimitive(TreeNode $ast)
    {
        $value = $ast->getValueValue();

        switch ($ast->getValueToken()) {
            case 'T_NUMBER_VALUE':
                return Str::contains('.', $value) ? (float)$value : (int)$value;
            case 'T_NULL':
                return null;
            case 'T_BOOL_TRUE':
            case 'T_BOOL_FALSE':
                return mb_strtolower($value, 'UTF-8') === 'true';
        }

        return (string)$value;
    }

    /**
     * TODO Bad code. Improve it
     * @param TreeNode $ast
     * @return object
     * @throws BrokenAstException
     */
    private function parseObject(TreeNode $ast)
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            [$key, $value] = [null, null];
            /** @var TreeNode $part */
            foreach ($child->getChildren() as $part) {
                switch ($part->getId()) {
                    case '#Name':
                        $key = $part->getChild(0)->getValueValue();
                        break;
                    case '#Value':
                        $value = $this->parse($part);
                        break;
                }
            }

            $result[$key] = $value;
        }

        return (object)$result;
    }

    /**
     * @param TreeNode $ast
     * @return array
     * @throws BrokenAstException
     */
    private function parseArray(TreeNode $ast): array
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            $result[] = $this->parse($child);
        }

        return $result;
    }
}
