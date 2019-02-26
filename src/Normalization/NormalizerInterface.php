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
 * Interface NormalizerInterface
 */
interface NormalizerInterface
{
    /**
     * @var int
     */
    public const LIST = 0x01;

    /**
     * @var int
     */
    public const NON_NULL = 0x02;

    /**
     * @var int
     */
    public const LIST_OF_NON_NULLS = 0x04;

    /**
     * @var int
     */
    public const TYPE_SCALAR = 0x08;

    /**
     * @param mixed $result
     * @param int $options
     * @return mixed|array|string|float|bool|int
     */
    public function normalize($result, int $options = 0);
}
