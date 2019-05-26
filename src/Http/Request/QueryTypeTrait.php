<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

use Railt\Http\Request\QueryTypeInterface as Type;

/**
 * Trait QueryTypeTrait
 */
trait QueryTypeTrait
{
    /**
     * @var string
     */
    protected $type = Type::TYPE_QUERY;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isType(string $type): bool
    {
        return \strtolower($type) === $this->type;
    }

    /**
     * @return bool
     */
    public function isQuery(): bool
    {
        return $this->isType(Type::TYPE_QUERY);
    }

    /**
     * @return bool
     */
    public function isMutation(): bool
    {
        return $this->isType(Type::TYPE_MUTATION);
    }

    /**
     * @return bool
     */
    public function isSubscription(): bool
    {
        return $this->isType(Type::TYPE_SUBSCRIPTION);
    }
}
