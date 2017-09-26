<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Behavior;

use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Behavior\Inputable;

/**
 * Trait BaseTypeIndicator
 * @mixin AllowsTypeIndication
 */
trait BaseTypeIndicator
{
    /**
     * @var Inputable
     */
    protected $type;

    /**
     * @var bool
     */
    protected $isNonNull = false;

    /**
     * @var bool
     */
    protected $isList = false;

    /**
     * @var bool
     */
    protected $isNonNullList = false;

    /**
     * @return Inputable
     */
    public function getType(): Inputable
    {
        return $this->resolve()->type;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->resolve()->isList;
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->resolve()->isNonNull;
    }

    /**
     * @return bool
     */
    public function isNonNullList(): bool
    {
        return $this->resolve()->isList && $this->isNonNullList;
    }
}
