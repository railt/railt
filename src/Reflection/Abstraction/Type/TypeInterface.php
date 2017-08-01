<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Abstraction\Type;

use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Interface TypeInterface
 * @package Serafim\Railgun\Reflection\Abstraction\Type
 */
interface TypeInterface
{
    /**
     * @return bool
     */
    public function nonNull(): bool;

    /**
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @return NamedDefinitionInterface
     */
    public function getDefinition(): NamedDefinitionInterface;
}
