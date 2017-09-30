<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Contracts\Types\ScalarType;

/**
 * Class BaseScalar
 */
abstract class BaseScalar extends BaseNamedType implements ScalarType
{
    /**
     * Scalar can be overriden by another scalar.
     * @todo Implementation requires resolving of #369
     * @see https://github.com/facebook/graphql/issues/369
     *
     * @param NamedTypeInterface $other
     * @return bool
     */
    public function canBeOverridenBy($other): bool
    {
        return $other instanceof ScalarType;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }
}
