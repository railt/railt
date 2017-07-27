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

/**
 * Class Reflection
 * @package Serafim\Railgun\Compiler\Reflection
 */
abstract class Reflection
{
    /**
     * @var TreeNode
     */
    private $ast;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * Reflection constructor.
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    public function __construct(TreeNode $ast, Dictionary $dictionary)
    {
        $this->ast = $ast;
        $this->dictionary = $dictionary;
    }

    /**
     * @return null|string
     */
    abstract public function getName(): ?string;

    /**
     * @return string
     */
    public function __toString(): string
    {
        $name = $this->getName() ?? 'default';

        return static::class . '#' . $name;
    }
}
