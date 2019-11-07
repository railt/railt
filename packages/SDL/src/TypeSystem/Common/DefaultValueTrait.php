<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\DefaultValueAwareInterface;

/**
 * @mixin DefaultValueAwareInterface
 */
trait DefaultValueTrait
{
    /**
     * @var mixed
     */
    public $defaultValue;

    /**
     * @var bool
     */
    public bool $hasDefaultValue = false;

    /**
     * {@inheritDoc}
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }
}
