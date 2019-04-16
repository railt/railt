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
 * Class IterableAsListNormalizer
 */
class IterableAsListNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $factory;

    /**
     * IterableAsListNormalizer constructor.
     *
     * @param NormalizerInterface $factory
     */
    public function __construct(NormalizerInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param mixed $result
     * @param ContextInterface|Context $context
     * @return array|bool|float|int|mixed|string
     * @throws \LogicException
     */
    public function normalize($result, ContextInterface $context)
    {
        if (! \is_iterable($result) || ! $context->isList()) {
            return $result;
        }

        $output = [];

        foreach ($result as $key => $value) {
            $output[$key] = $this->factory->normalize($value, $context->getItemContext());
        }

        return $output;
    }
}
