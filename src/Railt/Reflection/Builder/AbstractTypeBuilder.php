<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Railt\Reflection\Builder\Support\Deprecation;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Class AbstractTypeBuilder
 */
abstract class AbstractTypeBuilder extends AbstractBuilder implements TypeInterface
{
    use Deprecation;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return \class_basename(static::class);
    }
}
