<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\SchemaType;

/**
 * Class BaseSchema
 */
abstract class BaseSchema extends BaseType implements SchemaType
{
    /**
     * @var ObjectType
     */
    protected $query;

    /**
     * @var ObjectType|null
     */
    protected $mutation;

    /**
     * @var ObjectType|null
     */
    protected $subscription;

    /**
     * @return ObjectType
     */
    public function getQuery(): ObjectType
    {
        return $this->compiled()->query;
    }

    /**
     * @return null|ObjectType
     */
    public function getMutation(): ?ObjectType
    {
        return $this->compiled()->mutation;
    }

    /**
     * @return bool
     */
    public function hasMutation(): bool
    {
        return $this->compiled()->mutation instanceof ObjectType;
    }

    /**
     * @return null|ObjectType
     */
    public function getSubscription(): ?ObjectType
    {
        return $this->compiled()->subscription;
    }

    /**
     * @return bool
     */
    public function hasSubscription(): bool
    {
        return $this->compiled()->subscription instanceof ObjectType;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Schema';
    }
}
