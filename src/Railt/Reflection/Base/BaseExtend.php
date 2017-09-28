<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Support\Resolving;
use Railt\Reflection\Contracts\Types\ExtendType;
use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class BaseExtend
 */
abstract class BaseExtend implements ExtendType
{
    use Resolving;

    /**
     * @var ObjectType
     */
    protected $type;

    /**
     * @return ObjectType
     */
    public function getRelatedType(): ObjectType
    {
        return $this->resolve()->type;
    }
}
