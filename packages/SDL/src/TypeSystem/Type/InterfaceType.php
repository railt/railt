<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use Railt\SDL\TypeSystem\Common\FieldsTrait;
use Railt\SDL\TypeSystem\Common\InterfacesTrait;

/**
 * {@inheritDoc}
 */
class InterfaceType extends NamedType implements InterfaceTypeInterface
{
    use FieldsTrait;
    use InterfacesTrait;

    /**
     * @return string
     */
    public function getKind(): string
    {
        return 'INTERFACE';
    }
}
