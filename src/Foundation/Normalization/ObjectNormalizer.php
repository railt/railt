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
 * Class ObjectNormalizer
 */
class ObjectNormalizer extends Normalizer
{
    /**
     * @param mixed $result
     * @param FieldDefinition $field
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, FieldDefinition $field)
    {
        if (\is_object($result)) {
            return $this->isScalar($field)
                ? $this->renderScalar($result)
                : $this->renderObject($result);
        }

        return $result;
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    private function renderScalar($result)
    {
        if (\method_exists($result, '__toString')) {
            return (string)$result;
        }

        if (\property_exists($result, 'value')) {
            return $result->value;
        }

        return $result;
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    private function renderObject($result)
    {
        return \json_decode(\json_encode($result), true);
    }
}
