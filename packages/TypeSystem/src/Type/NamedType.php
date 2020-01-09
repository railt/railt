<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Type;

use Railt\TypeSystem\Common\NameTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * {@inheritDoc}
 */
abstract class NamedType extends Type implements NamedTypeInterface
{
    use NameTrait;
    use DescriptionTrait;
}
