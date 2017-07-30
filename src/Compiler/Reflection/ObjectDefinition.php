<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Autoloader;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Compiler\Reflection\Support\TypeRelated;

/**
 * Class ObjectDefinition
 * @package Serafim\Railgun\Compiler\Reflection
 */
class ObjectDefinition extends Definition
{
    use TypeRelated;

    /**
     * @var array|InterfaceDefinition[]
     */
    private $interfaces = [];

    /**
     * @return string
     */
    public static function getType(): string
    {
        return 'Object';
    }

    /**
     * @return string
     */
    public static function getAstId(): string
    {
        return '#ObjectDefinition';
    }

    /**
     * @return iterable
     */
    public function getInterfaces(): iterable
    {
        return $this->interfaces;
    }

    /**
     * @internal
     * @param TreeNode $node
     * @param Dictionary $dictionary
     * @throws NotReadableException
     * @throws SemanticException
     * @throws TypeNotFoundException
     * @throws UnexpectedTokenException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     */
    public function compile(TreeNode $node, Dictionary $dictionary): void
    {
        switch ($node->getId()) {
            case '#Implements':
                /** @var TreeNode $child */
                foreach ($node->getChildren() as $child) {
                    $this->interfaces[] = $this->loadRelation($child, $dictionary);
                }
        }
    }
}
