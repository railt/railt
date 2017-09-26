<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Contracts\Types\ExtendType;
use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class BaseExtend
 */
abstract class BaseExtend extends BaseNamedType implements ExtendType
{
    /**
     * @var ObjectType
     */
    protected $type;

    /**
     * @return ObjectType
     */
    public function getType(): ObjectType
    {
        return $this->resolve()->type;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Extend';
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'type',
        ]);
    }
}
