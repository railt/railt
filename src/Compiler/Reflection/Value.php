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
 * Class Value
 * @package Serafim\Railgun\Compiler\Reflection
 */
class Value
{
    /**
     * @throws \LogicException
     */
    public function getValue()
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return bool
     * @throws \LogicException
     */
    public function isNotNull(): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
