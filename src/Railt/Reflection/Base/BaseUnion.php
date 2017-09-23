<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Containers\BaseTypesContainer;
use Railt\Reflection\Contracts\Types\UnionType;

/**
 * Class BaseUnion
 */
abstract class BaseUnion extends BaseNamedType implements UnionType
{
    use BaseTypesContainer;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Union';
    }
}
