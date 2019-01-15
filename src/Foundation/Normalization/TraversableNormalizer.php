<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Normalization;

use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class TraversableNormalizer
 */
class TraversableNormalizer extends Normalizer
{
    /**
     * @param mixed $result
     * @param FieldDefinition $field
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, FieldDefinition $field)
    {
        if ($result instanceof \Traversable) {
            return \iterator_to_array($result);
        }

        return $result;
    }
}
