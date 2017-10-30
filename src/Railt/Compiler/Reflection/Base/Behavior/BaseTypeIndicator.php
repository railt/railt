<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Behavior;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Trait BaseTypeIndicator
 * @mixin AllowsTypeIndication
 */
trait BaseTypeIndicator
{
    /**
     * @var TypeDefinition
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
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return $this->type;
    }

    /**
     * The non-null type
     *
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->isNonNull;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->isList;
    }

    /**
     * The list of non-nulls
     *
     * @return bool
     */
    public function isListOfNonNulls(): bool
    {
        return $this->isList && $this->isListOfNonNulls;
    }
}
