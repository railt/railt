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
 * Interface ProvidesTypeRegistryInterface
 * @package Serafim\Railgun\Contracts
 */
interface ProvidesTypeRegistryInterface
{
    /**
     * @return TypesRegistryInterface
     */
    public function getRegistry(): TypesRegistryInterface;
}
