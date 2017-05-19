<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Registrars;

/**
 * Interface TypeDefinitionRegistrarInterface
 * @package Serafim\Railgun\Contracts\Registrars
 */
interface TypeDefinitionRegistrarInterface
{
    /**
     * @return TypeDefinitionRegistrarInterface
     */
    public function many(): TypeDefinitionRegistrarInterface;

    /**
     * @return TypeDefinitionRegistrarInterface
     */
    public function single(): TypeDefinitionRegistrarInterface;

    /**
     * @return TypeDefinitionRegistrarInterface
     */
    public function nullable(): TypeDefinitionRegistrarInterface;

    /**
     * @return TypeDefinitionRegistrarInterface
     */
    public function notNull(): TypeDefinitionRegistrarInterface;
}
