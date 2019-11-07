<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem\Type;

use Railt\SDL\TypeSystem\Common\NameTrait;
use Railt\SDL\TypeSystem\Common\DescriptionTrait;
use Railt\SDL\TypeSystem\Common\DeprecationTrait;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Common\DeprecationAwareInterface;

/**
 * {@inheritDoc}
 */
abstract class NamedType extends Type implements
    NamedTypeInterface,
    DeprecationAwareInterface
{
    use NameTrait;
    use DescriptionTrait;
    use DeprecationTrait;
}
