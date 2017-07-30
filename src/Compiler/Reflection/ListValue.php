<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection;

/**
 * Class ListValue
 * @package Serafim\Railgun\Compiler\Reflection
 */
class ListValue extends Value
{
    /**
     * @return Value
     * @throws \LogicException
     */
    public function getValue(): Value
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
