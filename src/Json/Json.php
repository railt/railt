<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Json\Rfc7159\NativeJsonDecoder;
use Railt\Json\Rfc7159\NativeJsonEncoder;

/**
 * Class Json
 */
class Json extends Facade
{
    /**
     * @param int|null $options
     * @return JsonEncoderInterface
     */
    public static function encoder(int $options = null): JsonEncoderInterface
    {
        if ($options === null) {
            return new NativeJsonEncoder();
        }

        return (new NativeJsonEncoder())->setOptions($options);
    }

    /**
     * @param int|null $options
     * @return JsonDecoderInterface
     */
    public static function decoder(int $options = null): JsonDecoderInterface
    {
        if ($options === null) {
            return new NativeJsonDecoder();
        }

        return (new NativeJsonDecoder())->setOptions($options);
    }
}
