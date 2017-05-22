<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts;

/**
 * Interface SchemaInterface
 * @package Serafim\Railgun\Contracts
 */
interface SchemaInterface
{
    /**
     * @param string $action
     * @param \Closure $then
     * @return SchemaInterface
     */
    public function extend(string $action, \Closure $then): SchemaInterface;
}
