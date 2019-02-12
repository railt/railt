<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Json;

use Railt\Json\Json;

/**
 * Class JsonDecoderTestCase
 */
class JsonDecoderTestCase extends AbstractDecoderTestCase
{
    /**
     * @param string $value
     * @param int $options
     * @return array|mixed
     * @throws \Railt\Json\Exception\JsonException
     */
    protected function decode(string $value, int $options = 0)
    {
        return Json::decoder()
            ->setOptions($options)
            ->decode($value);
    }
}
