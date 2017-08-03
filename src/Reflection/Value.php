<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Support\Str;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Value
 * @package Serafim\Railgun\Reflection
 */
final class Value
{
    /**
     * @var DocumentTypeInterface
     */
    private $document;

    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * Value constructor.
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     */
    public function __construct(DocumentTypeInterface $document, TreeNode $ast)
    {
        $this->document = $document;
        $this->ast = $ast;
    }

    /**
     * @param TreeNode $ast
     * @return object
     * @throws \LogicException
     */
    private function parseObject(TreeNode $ast)
    {
        $result = [];

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            [$key, $value, $hasKey, $hasValue] = [null, null, false, false];
            /** @var TreeNode $part */
            foreach ($child->getChildren() as $part) {
                switch ($part->getId()) {
                    case '#Name':
                        $hasKey = true;
                        $key = $part->getChild(0)->getValueValue();
                        break;
                    case '#Value':
                        $hasValue = true;
                        $value = $this->parse($part);
                        break;
                }
            }

            if (!$hasKey || !$hasValue) {
                throw new \LogicException('Error while parsing object');
            }

            $result[$key] = $value;
        }

        return (object)$result;
    }

    /**
     * @param TreeNode $ast
     * @return array
     * @throws \LogicException
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

    /**
     * @param TreeNode $ast
     * @return mixed
     * @throws \LogicException
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
                return Str::lower($value) === 'true';
        }

        return (string)$value;
    }

    /**
     * @param TreeNode $ast
     * @return array|mixed|object
     * @throws \LogicException
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

        throw new \LogicException('Error while parsing ' . dump($child));
    }

    /**
     * @return array|mixed|object
     * @throws \LogicException
     */
    public function compile()
    {
        return $this->parse($this->ast);
    }

    /**
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     * @return mixed
     * @throws \LogicException
     */
    public static function new(DocumentTypeInterface $document, TreeNode $ast)
    {
        return (new Value($document, $ast))->compile();
    }
}
