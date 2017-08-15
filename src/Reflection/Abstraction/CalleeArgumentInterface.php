<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Abstraction;

/**
 * Interface CalleeArgumentInterface
 * @package Railgun\Reflection\Abstraction
 */
interface CalleeArgumentInterface extends NamedDefinitionInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return NamedDefinitionInterface
     */
    public function getType(): NamedDefinitionInterface;
}

