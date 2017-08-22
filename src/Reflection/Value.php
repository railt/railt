<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Support\Str;
use Railt\Exceptions\IndeterminateBehaviorException;
use Railt\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Value
 * @package Railt\Reflection
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
     * TODO Bad code. Improve it
     * @param TreeNode $ast
     * @return object
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
                throw IndeterminateBehaviorException::new('Error while parsing object');
            }

            $result[$key] = $value;
        }

        return (object)$result;
    }

    /**
     * @param TreeNode $ast
     * @return array
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

        throw IndeterminateBehaviorException::new('Error while parsing ' . dump($child));
    }

    /**
     * @return array|mixed|object
     */
    public function compile()
    {
        return $this->parse($this->ast);
    }

    /**
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     * @return mixed
     */
    public static function new(DocumentTypeInterface $document, TreeNode $ast)
    {
        return (new Value($document, $ast))->compile();
    }
}
