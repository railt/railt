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
use Serafim\Railgun\Compiler\Dictionary;

/**
 * TODO Remove it in future (for development only)
 *
 * Class Stub
 * @package Serafim\Railgun\Reflection
 */
final class Stub extends Definition
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     * @return void
     */
    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        $this->data[$ast->getId()] = $ast;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        return $this->data[@substr($name, 3)] ?? null;
    }
}
