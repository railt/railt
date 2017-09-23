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
    protected $nonNull = false;

    /**
     * @var bool
     */
    protected $list = false;

    /**
     * @var bool
     */
    protected $nonNullList = false;

    /**
     * @return Inputable
     */
    public function getType(): Inputable
    {
        return $this->compiled()->type;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->compiled()->list;
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->compiled()->nonNull;
    }

    /**
     * @return bool
     */
    public function isNonNullList(): bool
    {
        return $this->compiled()->list && $this->compiled()->nonNullList;
    }
}
