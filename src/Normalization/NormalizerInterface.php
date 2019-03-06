<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization;

use Railt\Normalization\Context\ContextInterface;

/**
 * Interface NormalizerInterface
 */
interface NormalizerInterface
{
    /**
     * @param mixed $result
     * @param ContextInterface $context
     * @return mixed|array|string|float|bool|int
     */
    public function normalize($result, ContextInterface $context);
}
