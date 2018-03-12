<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Store;

/**
 * Class ScalarBox
 * @deprecated This is scalar container for future scalar deep resolvers implementation.
 */
final class ScalarBox extends BaseBox
{
    /**
     * Box constructor.
     * @param mixed $data
     * @param array $serialized
     */
    public function __construct($data, $serialized)
    {
        \assert(\is_scalar($serialized));

        parent::__construct($data, $serialized);
    }

    /**
     * @return int|string|float|float
     */
    public function getResponse()
    {
        $result = parent::getResponse();

        if (\is_array($result)) {
            return \json_encode($result);
        }

        if (\is_object($result)) {
            if (\method_exists($result, '__toString')) {
                return (string)$result;
            }

            return \json_encode($result);
        }

        return $result;
    }
}
