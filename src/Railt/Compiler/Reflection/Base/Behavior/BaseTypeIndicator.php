<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Behavior;

use Railt\Compiler\Reflection\Base\Resolving;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;

/**
 * Trait BaseTypeIndicator
 * @mixin AllowsTypeIndication
 * @mixin Resolving
 */
trait BaseTypeIndicator
{
    /**
     * @var Definition
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
    protected $isListOfNonNulls = false;

    /**
     * @return Definition|mixed
     */
    public function getType()
    {
        return $this->resolve()->type;
    }

    /**
     * The non-null type
     *
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->resolve()->isNonNull;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->resolve()->isList;
    }

    /**
     * The list of non-nulls
     *
     * @return bool
     */
    public function isListOfNonNulls(): bool
    {
        return $this->resolve()->isList && $this->isListOfNonNulls;
    }
}
