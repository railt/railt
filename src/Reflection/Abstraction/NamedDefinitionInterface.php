<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction;

/**
 * Interface NamedDefinitionInterface
 * @package Serafim\Railgun\Reflection\Abstraction
 */
interface NamedDefinitionInterface extends DefinitionInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
