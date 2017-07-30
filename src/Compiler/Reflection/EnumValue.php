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
 * Class EnumValue
 * @package Serafim\Railgun\Compiler\Reflection
 */
class EnumValue
{
    /**
     * @return string
     * @throws \LogicException
     */
    public function getValue(): string
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return iterable|Directive[]
     * @throws \LogicException
     */
    public function getDirectives(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
