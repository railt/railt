<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Normalization\Normalizer;

use Railt\Extension\Normalization\Context\ContextInterface;
use Railt\Extension\Normalization\NormalizerInterface;
use Railt\Json\Json;

/**
 * Class ObjectAsStringNormalizer
 */
class ObjectAsScalarNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $result
     * @param ContextInterface $context
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, ContextInterface $context)
    {
        if (! \is_object($result) || ! $context->isScalar()) {
            return $result;
        }

        //
        // Render as string using the __toString() method.
        //
        if (\method_exists($result, '__toString')) {
            return (string)$result;
        }

        //
        // Otherwise try to serialize in JSON string.
        //
        return Json::encode($result);
    }
}
