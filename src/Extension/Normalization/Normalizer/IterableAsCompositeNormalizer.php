<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Normalization\Normalizer;

use Railt\Extension\Normalization\Context\Context;
use Railt\Extension\Normalization\Context\ContextInterface;
use Railt\Extension\Normalization\NormalizerInterface;

/**
 * Class IterableAsCompositeNormalizer
 */
class IterableAsCompositeNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $result
     * @param ContextInterface|Context $context
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($result, ContextInterface $context)
    {
        if ($result instanceof \Traversable && ! $context->isList() && ! $context->isScalar()) {
            return \iterator_to_array($result);
        }

        return $result;
    }
}
