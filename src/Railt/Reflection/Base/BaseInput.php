<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Containers\BaseArgumentsContainer;
use Railt\Reflection\Contracts\Types\InputType;

/**
 * Class BaseInput
 */
abstract class BaseInput extends BaseNamedType implements InputType
{
    use BaseArgumentsContainer;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Input';
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'arguments',
        ]);
    }
}
