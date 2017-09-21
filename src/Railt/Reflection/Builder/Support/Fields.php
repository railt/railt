<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Railt\Reflection\Contracts\Containers\HasFields;
use Railt\Reflection\Contracts\Types\FieldType;

/**
 * Trait Fields
 * @mixin HasFields
 */
trait Fields
{
    public function getFields(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function hasField(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getField(string $name): ?FieldType
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    public function getNumberOfFields(): int
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
