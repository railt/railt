<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Value;

use Railt\CodeGenerator\GeneratorInterface;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class CompositeValueGenerator
 */
abstract class CompositeValueGenerator extends ValueGenerator
{
    /**
     * @param ValueInterface $value
     * @return GeneratorInterface
     */
    protected function create(ValueInterface $value): GeneratorInterface
    {
        return $this->value($value, [
            static::CONFIG_DEPTH => $this->depth() + 1,
        ]);
    }
}
