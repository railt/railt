<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Behavior\BaseChild;
use Railt\Reflection\Base\Behavior\BaseTypeIndicator;
use Railt\Reflection\Base\Containers\BaseArgumentsContainer;
use Railt\Reflection\Contracts\Types\FieldType;

/**
 * Class BaseField
 */
abstract class BaseField extends BaseNamedType implements FieldType
{
    use BaseChild;
    use BaseTypeIndicator;
    use BaseArgumentsContainer;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Field';
    }



    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'type',
            'isList',
            'isNonNull',
            'isNonNullList',
            'parent',
            'arguments',
        ]);
    }
}
