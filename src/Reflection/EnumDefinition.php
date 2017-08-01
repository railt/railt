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
use Serafim\Railgun\Reflection\Abstraction\EnumTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\EnumValueInterface;
use Serafim\Railgun\Reflection\Common\HasDirectives;

/**
 * Class EnumDefinition
 * @package Serafim\Railgun\Reflection
 */
class EnumDefinition extends Definition implements EnumTypeInterface
{
    use HasDirectives;

    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getValues(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function hasValue(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getValue(string $name): ?EnumValueInterface
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
