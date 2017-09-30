<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Base\Behavior\BaseName;
use Railt\Reflection\Base\Containers\BaseDirectivesContainer;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;

/**
 * Class BaseNamedType
 */
abstract class BaseNamedType extends BaseType implements NamedTypeInterface
{
    use BaseName;
    use BaseDirectivesContainer;

    /**
     * @param NamedTypeInterface $other
     * @return bool
     */
    public function canBeOverridenBy($other): bool
    {
        if ($other instanceof NamedTypeInterface) {
            //     type === type              && type Name === type Name
            return $this->isSameTypes($other) && $this->isSameName($other);
        }

        return false;
    }

    /**
     * @param NamedTypeInterface $other
     * @return bool
     */
    protected function isSameTypes(NamedTypeInterface $other): bool
    {
        return $this instanceof $other;
    }

    /**
     * @param NamedTypeInterface $other
     * @return bool
     */
    protected function isSameName(NamedTypeInterface $other): bool
    {
        return $this->getName() === $other->getName();
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'name',
            'description',
            'directives'
        ]);
    }
}
