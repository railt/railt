<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Definitions;

use Railt\Compiler\Reflection\Contracts\Behavior\Deprecatable;

/**
 * Interface TypeDefinition
 */
interface TypeDefinition extends Definition, Deprecatable
{
    /**
     * Returns the name of definition instance.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the name of type.
     *
     * @return string
     */
    public function getTypeName(): string;

    /**
     * Returns a short description of definition.
     *
     * @return string
     */
    public function getDescription(): string;
}
