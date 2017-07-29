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
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Compiler\Exceptions\TypeException;
use Serafim\Railgun\Compiler\Exceptions\TypeNotFoundException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;
use Serafim\Railgun\Compiler\Reflection\Support\TypeRelated;

/**
 * Class SchemaDefinition
 * @package Serafim\Railgun\Compiler\Reflection
 */
class SchemaDefinition extends Definition
{
    use TypeRelated;

    /**
     * @var ObjectDefinition
     */
    private $query;

    /**
     * @var ObjectDefinition
     */
    private $mutation;

    /**
     * @return string
     */
    public static function getType(): string
    {
        return 'Schema';
    }

    /**
     * @return string
     */
    public static function getAstId(): string
    {
        return '#SchemaDefinition';
    }

    /**
     * @internal
     * @param TreeNode $node
     * @param Autoloader $loader
     * @return void
     * @throws SemanticException
     * @throws TypeException
     * @throws \OutOfRangeException
     * @throws \RuntimeException
     * @throws NotReadableException
     * @throws TypeNotFoundException
     * @throws UnexpectedTokenException
     */
    public function compile(TreeNode $node, Autoloader $loader): void
    {
        switch ($node->getId()) {
            case '#Query':
                if ($this->query !== null) {
                    throw new TypeException('Can not redeclare already defined query',
                        $this->getContext()->getFileName());
                }
                $this->query = $this->loadRelation($node->getChild(0), $loader);
                break;

            case '#Mutation':
                if ($this->mutation !== null) {
                    throw new TypeException('Can not redeclare already defined mutation',
                        $this->getContext()->getFileName());
                }
                $this->mutation = $this->loadRelation($node->getChild(0), $loader);
                break;
        }
    }

    public function getQuery(): ObjectDefinition
    {

    }
}
