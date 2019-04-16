<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json\Rfc7159;

use Railt\Component\Json\Exception\JsonException;
use Railt\Component\Json\JsonEncoder;

/**
 * Class NativeJsonEncoder
 */
class NativeJsonEncoder extends JsonEncoder
{
    use ErrorHandlerTrait;

    /**
     * NativeJsonEncoder constructor.
     */
    public function __construct()
    {
        \assert(\function_exists('\\json_encode'), 'PHP JSON extension required');
    }

    /**
     * Wrapper for JSON encoding logic with predefined options that
     * throws a Railt\Component\Json\Exception\JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param mixed $data
     * @param int|null $options
     * @return string
     * @throws JsonException
     */
    public function encode($data, int $options = null): string
    {
        if ($options !== null) {
            return (clone $this)->encode($data);
        }

        return $this->wrap(function () use ($data) {
            return @\json_encode($data, $this->getOptions(), $this->getRecursionDepth());
        });
    }
}
