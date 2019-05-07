<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Json\Json5\Json5Decoder;

/**
 * Class Json5
 */
class Json5 extends Json
{
    /**
     * @var int
     */
    public const FORCE_JSON5_DECODER = 8388608;

    /**
     * @internal Reserved for future releases.
     * @var int
     */
    public const FORCE_JSON5_ENCODER = 8388608;

    /**
     * @param int|null $options
     * @return JsonDecoderInterface
     */
    public static function decoder(int $options = null): JsonDecoderInterface
    {
        if ($options === null) {
            return new Json5Decoder();
        }

        return (new Json5Decoder())->setOptions($options);
    }
}
