<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Containers\BaseFieldsContainer;
use Railt\Reflection\Contracts\Types\InterfaceType;

/**
 * Class BaseInterface
 */
abstract class BaseInterface extends BaseNamedType implements InterfaceType
{
    use BaseFieldsContainer;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Interface';
    }
}
