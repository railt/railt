<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Io\Readable;
use Railt\Json\Exception\JsonException;

/**
 * Class JsonDecoder
 */
abstract class JsonDecoder extends JsonRuntime implements JsonDecoderInterface
{
    /**
     * Automatically enables object to array convertation.
     *
     * Note: JSON_OBJECT_AS_ARRAY = 1
     *
     * @var int
     */
    protected $options = 1;

    /**
     * @param Readable $readable
     * @return array
     * @throws JsonException
     */
    public function read(Readable $readable): array
    {
        return $this->decode($readable->getContents());
    }
}
