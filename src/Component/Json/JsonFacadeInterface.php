<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json;

/**
 * Interface JsonFacadeInterface
 */
interface JsonFacadeInterface
{
    /**
     * @param int|null $options
     * @return JsonEncoderInterface
     */
    public static function encoder(int $options = null): JsonEncoderInterface;

    /**
     * @param int|null $options
     * @return JsonDecoderInterface
     */
    public static function decoder(int $options = null): JsonDecoderInterface;
}
