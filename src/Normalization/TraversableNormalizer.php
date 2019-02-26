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
 * Class TraversableNormalizer
 */
class TraversableNormalizer extends Normalizer
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * TraversableNormalizer constructor.
     *
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param mixed $data
     * @param int $options
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($data, int $options = 0)
    {
        if (($options & static::LIST) && \is_iterable($data)) {
            $childOptions = $this->listItemOptions($options);
            $result = [];

            foreach ($data as $key => $value) {
                $result[$key] = $this->normalizer->normalize($value, $childOptions);
            }

            return $result;
        }

        if ($data instanceof \Traversable && ! ($options & static::LIST)) {
            return \iterator_to_array($data);
        }

        return $data;
    }
}
