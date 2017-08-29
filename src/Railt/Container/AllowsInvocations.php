<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

/**
 * Interface AllowsInvocations
 * @package Railt\Container
 */
interface AllowsInvocations
{
    /**
     * @param string|callable|\ReflectionFunctionAbstract $action
     * @param array $params
     * @param string $namespace
     * @return mixed
     */
    public function call($action, array $params = [], string $namespace = '');
}
