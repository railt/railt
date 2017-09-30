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
     * Interface type can be overriden by interface or child object.
     *
     * @param NamedTypeInterface $other
     * @return bool
     */
    public function canBeOverridenBy($other): bool
    {
        if ($other instanceof InterfaceType) {
            return $this->isSameName($other);
        }

        if ($other instanceof ObjectType) {
            return $this->isImplementation($other);
        }

        return false;
    }

    /**
     * Is the Object type implements this Interface?
     *
     * @param ObjectType $type
     * @return bool
     */
    private function isImplementation(ObjectType $type): bool
    {
        return $type->hasInterface($this->getName());
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
