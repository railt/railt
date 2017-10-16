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
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;

/**
 * Trait BaseTypeIndicator
 * @mixin AllowsTypeIndication
 */
trait BaseTypeIndicator
{
    /**
     * @var NamedTypeDefinition
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
     * @return NamedTypeDefinition
     * @throws \Railt\Reflection\Exceptions\TypeNotFoundException
     */
    public function getType(): NamedTypeDefinition
    {
        return $this->resolve()->type;
    }

    /**
     * @param NamedTypeDefinition $type
     * @return void
     */
    public function setType(NamedTypeDefinition $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->resolve()->isList;
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
     * The list of non-nulls
     *
     * @return bool
     */
    public function isListOfNonNulls(): bool
    {
        return $this->resolve()->isList && $this->isListOfNonNulls;
    }

    /**
     * @return bool
     */
    private function isNullable(): bool
    {
        return ! $this->isNonNull();
    }
}
