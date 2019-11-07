<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Constraint;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Type\WrappingTypeInterface;

/**
 * Class WrappingType
 */
abstract class WrappingType extends Type implements WrappingTypeInterface
{
    /**
     * @var TypeInterface
     */
    public TypeInterface $ofType;

    /**
     * {@inheritDoc}
     */
    public function getOfType(): TypeInterface
    {
        return $this->ofType;
    }
}
