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
     * @return JsonDecoderInterface
     * @throws \LogicException
     */
    public static function decoder(): JsonDecoderInterface
    {
        return new Json5Decoder();
    }
}
