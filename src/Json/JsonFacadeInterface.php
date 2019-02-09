<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

/**
 * Interface JsonFacadeInterface
 */
interface JsonFacadeInterface
{
    /**
     * @return JsonEncoderInterface
     */
    public static function encoder(): JsonEncoderInterface;

    /**
     * @return JsonDecoderInterface
     */
    public static function decoder(): JsonDecoderInterface;
}
