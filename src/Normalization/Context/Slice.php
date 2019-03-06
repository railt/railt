<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization\Context;

/**
 * Class Slice
 */
class Slice extends Context
{
    /**
     * @return bool
     */
    public function isList(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->field->isListOfNonNulls();
    }
}
