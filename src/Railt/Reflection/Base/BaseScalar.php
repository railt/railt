<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Contracts\Types\ScalarType;

/**
 * Class BaseScalar
 */
abstract class BaseScalar extends BaseNamedType implements ScalarType
{
    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [

        ]);
    }
}
