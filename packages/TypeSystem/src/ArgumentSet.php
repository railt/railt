<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Ramsey\Collection\AbstractSet;

/**
 * Class ArgumentSet
 */
final class ArgumentSet extends AbstractSet
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Argument::class;
    }
}
