<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization;

/**
 * Class Normalizer
 */
abstract class Normalizer implements NormalizerInterface
{
    /**
     * @param int $options
     * @return int
     */
    public function listItemOptions(int $options): int
    {
        $result = 0;

        if ($options & static::LIST_OF_NON_NULLS) {
            $result |= static::NON_NULL;
        }

        if ($options & static::TYPE_SCALAR) {
            $result |= static::TYPE_SCALAR;
        }

        return $result;
    }
}
