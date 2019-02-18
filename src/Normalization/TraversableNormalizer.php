<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization;

use Railt\SDL\Contracts\Dependent\FieldDefinition;

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
     * @param FieldDefinition $field
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($data, FieldDefinition $field)
    {
        if ($data instanceof \Traversable) {
            $result = [];

            foreach ($data as $key => $value) {
                $result[$key] = $this->normalizer->normalize($value, $field);
            }

            return $result;
        }

        return $data;
    }
}
