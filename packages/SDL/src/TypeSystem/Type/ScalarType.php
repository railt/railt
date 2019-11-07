<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use GraphQL\Contracts\TypeSystem\Type\ScalarTypeInterface;

/**
 * {@inheritDoc}
 */
class ScalarType extends NamedType implements ScalarTypeInterface
{
    /**
     * @return string
     */
    public function getKind(): string
    {
        return 'SCALAR';
    }
}
