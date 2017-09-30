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
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Base\Containers\BaseFieldsContainer;

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

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'fields',
        ]);
    }
}
